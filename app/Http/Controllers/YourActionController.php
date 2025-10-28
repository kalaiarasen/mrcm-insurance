<?php

namespace App\Http\Controllers;

use App\Models\PolicyApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class YourActionController extends Controller
{
    /**
     * Display the for-your-action page with policy statistics and pending policies.
     */
    public function index()
    {
        // Count policies by status (only is_used = true)
        $newPolicies = PolicyApplication::where('status', 'submitted')->where('is_used', true)->count();
        $activePolicies = PolicyApplication::where('status', 'approved')->where('is_used', true)->count();
        $pendingPolicies = PolicyApplication::where('status', 'submitted')->where('is_used', true)->count();
        $rejectedPolicies = PolicyApplication::where('status', 'rejected')->where('is_used', true)->count();

        // Get all policies with related user data for the table (only is_used = true)
        $policies = PolicyApplication::with('user')
            ->where('is_used', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($policy) {
                return [
                    'id' => $policy->id,
                    'policy_id' => $policy->reference_number ?? null,
                    'type' => 'New', // You can adjust this based on submission_version
                    'status' => $policy->status,
                    'expiry_date' => $policy->user?->policyPricing?->policy_expiry_date ?? 'N/A',
                    'name' => $policy->user?->name ?? 'Unknown',
                    'policy_no' => $policy->reference_number ?? null,
                    'email' => $policy->user?->email ?? 'N/A',
                    'class' => $policy->user?->healthcareService?->coverage_type ?? 'General Cover',
                    'amount' => $policy->user?->policyPricing?->total_payable ?? null,
                ];
            });

        return view('pages.your-action.index', compact('newPolicies', 'activePolicies', 'pendingPolicies', 'rejectedPolicies', 'policies'));
    }

    /**
     * Display detailed view of a specific policy application.
     */
    public function show($id)
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
            'actionBy'
        ])
        ->where('id', $id)
        ->where('is_used', true)
        ->firstOrFail();

        return view('pages.your-action.show', compact('policyApplication'));
    }

    /**
     * Update the status of a policy application.
     */
    public function updateStatus(Request $request, $id)
    {
        $policyApplication = PolicyApplication::where('id', $id)
            ->where('is_used', true)
            ->firstOrFail();

        // Validate request
        $validated = $request->validate([
            'status' => 'required|in:new,approved,send_uw,active,processing,rejected,cancelled',
            'remarks' => 'nullable|string|max:5000',
        ]);

        $oldStatus = $policyApplication->status;
        $newStatus = $validated['status'];

        // Start transaction
        DB::beginTransaction();

        try {
            // Update basic fields
            $policyApplication->status = $newStatus;
            $policyApplication->remarks = $validated['remarks'];
            
            // Set action metadata for all status changes
            $policyApplication->action_by = Auth::id();
            $policyApplication->action_at = now();

            // Handle status-specific actions
            switch ($newStatus) {
                case 'approved':
                    // Generate reference number if not exists
                    if (!$policyApplication->reference_number) {
                        $policyApplication->reference_number = $this->generateReferenceNumber($policyApplication->user_id);
                    }
                    $policyApplication->approved_at = now();
                    break;

                case 'active':
                    // Ensure reference number exists for active policies
                    if (!$policyApplication->reference_number) {
                        $policyApplication->reference_number = $this->generateReferenceNumber($policyApplication->user_id);
                    }
                    break;
            }

            $policyApplication->save();

            // Update user application status
            $policyApplication->user->update([
                'application_status' => $newStatus,
            ]);

            DB::commit();

            return redirect()
                ->route('for-your-action.show', $policyApplication->id)
                ->with('success', "Application status updated from '{$oldStatus}' to '{$newStatus}' successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update policy application status', [
                'policy_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update application status. Please try again.');
        }
    }

    /**
     * Generate a unique reference number for the application
     * 
     * @param int $userId
     * @return string
     */
    private function generateReferenceNumber($userId)
    {
        return 'POL-' . date('Ymd') . '-' . str_pad($userId, 6, '0', STR_PAD_LEFT);
    }
}
