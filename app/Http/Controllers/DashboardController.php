<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Claim;
use App\Models\DashboardSetting;
use App\Models\User;
use App\Models\PolicyApplication;
use App\Models\QuotationRequest;
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
    public function index()
    {
        if(auth()->user()->hasRole('Client')) {
           $announcements = Announcement::latest()
            ->take(5)
            ->get();

            // Get all policies for the current user (for display table)
            $policies = PolicyApplication::with(['user.applicantProfile', 'user.healthcareService', 'policyPricing'])
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'DESC')
                ->get();

            // Get active policies from this year for claims modal (only active, created in current year)
            $activePoliciesForClaims = PolicyApplication::with(['user.applicantProfile', 'user.healthcareService', 'policyPricing'])
                ->where('user_id', auth()->id())
                ->where('customer_status', 'active')
                ->whereYear('created_at', now()->year)
                ->orderBy('created_at', 'ASC')
                ->get();

            // Get all claims for the current user
            $claims = Claim::with(['policyApplication', 'claimDocuments'])
                ->where('user_id', auth()->id())
                ->latest()
                ->take(10)
                ->get();

            // Get quotation requests for the current user
            $quotationRequests = QuotationRequest::with(['product', 'user'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            // Get dashboard settings
            $dashboardSetting = DashboardSetting::first();

            $totalUsers = User::count();
            $totalPolicies = PolicyApplication::where('user_id', auth()->id())->where('is_used', true)->count();
            $totalClaims = Claim::where('user_id', auth()->id())->count();
            $monthlyRevenue = 0;
            $walletAmount = auth()->user()->wallet_amount ?? 0;

            // Check for active Professional Indemnity policy
            $activeProfessionalIndemnityPolicy = PolicyApplication::with(['policyPricing'])
                ->where('user_id', auth()->id())
                ->whereIn('customer_status', ['active', 'paid', 'approved', 'processing'])
                ->latest()
                ->first();

            $hasActiveProfessionalIndemnity = $activeProfessionalIndemnityPolicy !== null;
            $renewalEligible = false;

            // Calculate renewal eligibility (6 months before expiry)
            if ($hasActiveProfessionalIndemnity && $activeProfessionalIndemnityPolicy->policyPricing) {
                $expiryDate = \Carbon\Carbon::parse($activeProfessionalIndemnityPolicy->policyPricing->policy_expiry_date);
                $sixMonthsBeforeExpiry = $expiryDate->copy()->subMonths(6);
                $now = now();
                
                // Eligible for renewal if current date is within 6 months of expiry
                $renewalEligible = $now->greaterThanOrEqualTo($sixMonthsBeforeExpiry);
            }

            $pendingPolicy = PolicyApplication::where('user_id', auth()->id())
                ->whereIn('customer_status', ['submitted', 'pay_now', 'paid', 'processing', 'rejected'])
                ->first();
            
            $hasPendingPolicy = $pendingPolicy !== null;

            return view('dashboard-client', compact(
                'announcements',
                'policies',
                'activePoliciesForClaims',
                'claims',
                'quotationRequests',
                'dashboardSetting',
                'totalUsers',
                'totalPolicies',
                'totalClaims',
                'monthlyRevenue',
                'walletAmount',
                'hasActiveProfessionalIndemnity',
                'renewalEligible',
                'activeProfessionalIndemnityPolicy',
                'hasPendingPolicy',
                'pendingPolicy'
            ));
        }

        return redirect()->route('for-your-action');
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
            'policyPricing',
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
                'name_on_card' => 'nullable|string|max:100',
                'nric_no' => 'nullable|string|max:50',
                'card_no' => 'nullable|string|max:50',
                'card_issuing_bank' => 'nullable|string|max:100',
                'card_type' => 'nullable|array',
                'expiry_month' => 'nullable|string',
                'expiry_year' => 'nullable|string',
                'relationship' => 'nullable|array',
                'authorize_payment' => 'required|accepted',
                'payment_type' => 'required|in:proof,credit_card',
            ], [
                'authorize_payment.required' => 'You must authorize the payment.',
                'authorize_payment.accepted' => 'You must agree to the authorization terms.',
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
                // Store credit card info
                $policyApplication->payment_method = 'credit_card';
                $policyApplication->name_on_card = $request->input('name_on_card');
                $policyApplication->nric_no = $request->input('nric_no');
                $policyApplication->card_no = $request->input('card_no');
                $policyApplication->card_issuing_bank = $request->input('card_issuing_bank');
                $policyApplication->card_type = $request->input('card_type');
                $policyApplication->expiry_month = $request->input('expiry_month');
                $policyApplication->expiry_year = $request->input('expiry_year');
                $policyApplication->relationship = $request->input('relationship');
                $policyApplication->authorize_payment = $request->input('authorize_payment') ? true : false;
                // Note: Card details will be submitted to Great Eastern for processing
            }

            // Generate sequential reference number when payment is received
            if (!$policyApplication->reference_number) {
                $policyApplication->reference_number = $this->generateReferenceNumber($policyApplication);
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

    /**
     * Generate sequential reference number that resets annually based on policy year
     * Format: MRCM26-0001, MRCM26-0002, etc.
     * Generated when payment is received
     * Resets based on policy_start_date from policy_pricings table
     * 
     * @param PolicyApplication $policyApplication
     * @return string
     */
    private function generateReferenceNumber(PolicyApplication $policyApplication)
    {
        // Get policy start date from policy_pricings table
        $policyPricing = $policyApplication->policyPricing;
        
        if (!$policyPricing || !$policyPricing->policy_start_date) {
            // Fallback: use current year + 1 if policy_start_date not available
            $policyYear = substr((string)(date('Y') + 1), -2);
        } else {
            // Extract year from policy_start_date and get last 2 digits
            $startYear = date('Y', strtotime($policyPricing->policy_start_date));
            $policyYear = substr((string)$startYear, -2);
        }
        
        // Get the highest reference number for this policy year (e.g., MRCM26-XXXX)
        // Query based on policy year in the reference number
        $lastPolicy = PolicyApplication::whereNotNull('reference_number')
            ->where('reference_number', 'LIKE', 'MRCM' . $policyYear . '-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(reference_number, "-", -1) AS UNSIGNED) DESC')
            ->first();
        
        if ($lastPolicy && preg_match('/MRCM(\d{2})-(\d{4})/', $lastPolicy->reference_number, $matches)) {
            $nextNumber = intval($matches[2]) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return 'MRCM' . $policyYear . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
