<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Claim;
use App\Models\User;
use App\Models\PolicyApplication;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        if(auth()->user()->hasRole('Client')) {
           $announcements = Announcement::latest()
            ->take(5)
            ->get();

            // Get all policies for the current user (for display table)
            $policies = PolicyApplication::with(['user.applicantProfile', 'user.healthcareService', 'user.policyPricing'])
                ->where('user_id', auth()->id())
                ->orderBy('updated_at', 'DESC')
                ->get();

            // Get active policies from this year for claims modal (only active, created in current year)
            $activePoliciesForClaims = PolicyApplication::with(['user.applicantProfile', 'user.healthcareService', 'user.policyPricing'])
                ->where('user_id', auth()->id())
                ->where('customer_status', 'active')
                ->whereYear('created_at', now()->year)
                ->orderBy('reference_number', 'ASC')
                ->get();

            // Get all claims for the current user
            $claims = Claim::with(['policyApplication', 'claimDocuments'])
                ->where('user_id', auth()->id())
                ->latest()
                ->take(10)
                ->get();

            $totalUsers = User::count();
            $totalPolicies = PolicyApplication::where('user_id', auth()->id())->where('is_used', true)->count();
            $totalClaims = Claim::where('user_id', auth()->id())->count();
            $monthlyRevenue = 0;

            return view('dashboard-client', compact(
                'announcements',
                'policies',
                'activePoliciesForClaims',
                'claims',
                'totalUsers',
                'totalPolicies',
                'totalClaims',
                'monthlyRevenue'
            ));
        }

        return view('dashboard');
    }

    /**
     * Get announcements for AJAX requests
     */
    public function getAnnouncements()
    {
        $announcements = Announcement::latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $announcements
        ]);
    }

    /**
     * Show client policy detail page
     */
    public function showPolicy($id)
    {
        // Find the policy application with all related data
        $policyApplication = PolicyApplication::with([
            'user.applicantProfile',
            'user.qualifications',
            'user.addresses',
            'user.applicantContact',
            'user.healthcareService',
            'user.policyPricing',
            'user.riskManagement',
            'user.insuranceHistory',
            'user.claimsExperience',
        ])
        ->where('id', $id)
        ->where('user_id', auth()->id()) // Ensure user can only view their own policies
        ->firstOrFail();

        return view('pages.client-policy.show', compact('policyApplication'));
    }

    /**
     * Upload payment document and update status to paid
     */
    public function uploadPayment(Request $request, $id)
    {
        $policyApplication = PolicyApplication::where('id', $id)
            ->where('user_id', auth()->id()) // Ensure user can only update their own policies
            ->firstOrFail();

        // Validate based on payment type
        $paymentType = $request->input('payment_type', 'proof');

        if ($paymentType === 'proof') {
            // Validate the payment document
            $request->validate([
                'payment_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
                'payment_type' => 'required|in:proof,credit_card',
            ]);
        } else {
            // Validate credit card details
            $request->validate([
                'cardholder_name' => 'required|string|max:100',
                'card_number' => ['required', 'regex:#^(\d{4}\s\d{4}\s\d{4}\s\d{4}|\d{16})$#'],
                'expiry_date' => ['required', 'regex:#^\d{2}/\d{2}$#'],
                'cvv' => ['required', 'regex:#^\d{3,4}$#'],
                'payment_type' => 'required|in:proof,credit_card',
            ], [
                'card_number.regex' => 'Please enter a valid 16-digit card number.',
                'expiry_date.regex' => 'Please enter expiry date in MM/YY format.',
                'cvv.regex' => 'Please enter a valid CVV (3-4 digits).',
            ]);
        }

        DB::beginTransaction();

        try {
            if ($paymentType === 'proof' && $request->hasFile('payment_document')) {
                // Store the payment document
                $file = $request->file('payment_document');
                $filename = 'payment_' . $policyApplication->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('payment-documents', $filename, 'public');

                // Delete old payment document if exists
                if ($policyApplication->payment_document) {
                    Storage::disk('public')->delete($policyApplication->payment_document);
                }

                $policyApplication->payment_document = $path;
            } elseif ($paymentType === 'credit_card') {
                // Store masked credit card info
                $cardNumber = str_replace(' ', '', $request->input('card_number'));
                $maskedCard = substr($cardNumber, -4);
                $maskedCard = str_repeat('*', 12) . $maskedCard;
                
                // Store credit card info (in production, use tokenization)
                $policyApplication->payment_method = 'credit_card';
                $policyApplication->card_holder_name = $request->input('cardholder_name');
                $policyApplication->card_last_four = substr($cardNumber, -4);
                // Note: In production, never store full card details in the database
                // Use a payment gateway like Stripe or 2Checkout
            }

            // Update policy application with payment status
            $policyApplication->customer_status = 'paid';
            $policyApplication->admin_status = 'paid';
            $policyApplication->payment_received_at = now();
            $policyApplication->save();

            DB::commit();

            $message = $paymentType === 'proof' 
                ? 'Payment document uploaded successfully! Your policy status has been updated to Paid.'
                : 'Credit card payment received successfully! Your policy status has been updated to Paid.';

            return redirect()
                ->route('dashboard')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to process payment', [
                'policy_id' => $id,
                'user_id' => auth()->id(),
                'payment_type' => $paymentType,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to process payment. Please try again.');
        }
    }
}
