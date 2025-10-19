<?php

namespace App\Http\Controllers;

use App\Models\PolicyApplication;
use Illuminate\Http\Request;

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
}
