<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
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

            // Get policies awaiting payment for the current user
            $policies = PolicyApplication::with(['user.applicantProfile', 'user.healthcareService', 'user.policyPricing'])
                ->where('user_id', auth()->id())
                ->where('customer_status', 'pay_now')
                ->where('is_used', true)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalUsers = User::count();
            $totalPolicies = PolicyApplication::where('user_id', auth()->id())->where('is_used', true)->count();
            $totalClaims = 0;
            $monthlyRevenue = 0;

            return view('dashboard-client', compact(
                'announcements',
                'policies',
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
        ->where('is_used', true)
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
            ->where('is_used', true)
            ->firstOrFail();

        // Validate the payment document
        $request->validate([
            'payment_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        DB::beginTransaction();

        try {
            // Store the payment document
            if ($request->hasFile('payment_document')) {
                $file = $request->file('payment_document');
                $filename = 'payment_' . $policyApplication->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('payment-documents', $filename, 'public');

                // Delete old payment document if exists
                if ($policyApplication->payment_document) {
                    Storage::disk('public')->delete($policyApplication->payment_document);
                }

                // Update policy application with payment document and change both statuses to 'paid'
                $policyApplication->payment_document = $path;
                $policyApplication->customer_status = 'paid';
                $policyApplication->admin_status = 'paid';
                $policyApplication->payment_received_at = now();
                $policyApplication->save();

                DB::commit();

                return redirect()
                    ->route('dashboard')
                    ->with('success', 'Payment document uploaded successfully! Your policy status has been updated to Paid.');
            }

            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to upload payment document. Please try again.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to upload payment document', [
                'policy_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to upload payment document. Please try again.');
        }
    }
}
