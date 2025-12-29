<?php

namespace App\View\Composers;

use App\Models\PolicyApplication;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class SidebarComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Only for authenticated Client users
        if (Auth::check() && Auth::user()->hasRole('Client')) {
            // Check for active Professional Indemnity policy
            $activeProfessionalIndemnityPolicy = PolicyApplication::with(['policyPricing'])
                ->where('user_id', Auth::id())
                ->whereIn('customer_status', ['active', 'paid', 'approved', 'processing'])
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

            // Check for pending/submitted policies (not active, not rejected)
            $pendingPolicy = PolicyApplication::where('user_id', Auth::id())
                ->whereIn('customer_status', ['submitted', 'pay_now', 'paid', 'processing'])
                ->whereNotIn('customer_status', ['rejected', 'cancelled'])
                ->first();
            
            $hasPendingPolicy = $pendingPolicy !== null;

            $view->with([
                'hasActiveProfessionalIndemnity' => $hasActiveProfessionalIndemnity,
                'renewalEligible' => $renewalEligible,
                'activeProfessionalIndemnityPolicy' => $activeProfessionalIndemnityPolicy,
                'hasPendingPolicy' => $hasPendingPolicy,
                'pendingPolicy' => $pendingPolicy
            ]);
        } else {
            // For non-client users, set default values
            $view->with([
                'hasActiveProfessionalIndemnity' => false,
                'renewalEligible' => false,
                'activeProfessionalIndemnityPolicy' => null,
                'hasPendingPolicy' => false,
                'pendingPolicy' => null
            ]);
        }
    }
}
