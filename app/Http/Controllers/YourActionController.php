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
     * Show the edit form for a policy application.
     */
    public function edit($id)
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
        ->where('is_used', true)
        ->firstOrFail();

        return view('pages.your-action.edit', compact('policyApplication'));
    }

    /**
     * Update the policy application data.
     */
    public function update(Request $request, $id)
    {
        $policyApplication = PolicyApplication::where('id', $id)
            ->where('is_used', true)
            ->firstOrFail();

        // Start transaction
        DB::beginTransaction();

        try {
            $data = $request->input('application_data');
            $user = $policyApplication->user;

            // Update User
            $user->update([
                'name' => $data['full_name'] ?? $user->name,
                'email' => $data['email_address'] ?? $user->email,
                'contact_no' => $data['contact_no'] ?? $user->contact_no,
            ]);

            // Update Applicant Profile
            if ($user->applicantProfile) {
                $user->applicantProfile->update([
                    'title' => $data['title'] ?? $user->applicantProfile->title,
                    'nationality_status' => $data['nationality_status'] ?? $user->applicantProfile->nationality_status,
                    'nric_number' => $data['nric_number'] ?? $user->applicantProfile->nric_number,
                    'passport_number' => $data['passport_number'] ?? $user->applicantProfile->passport_number,
                    'gender' => $data['gender'] ?? $user->applicantProfile->gender,
                    'registration_council' => $data['registration_council'] ?? $user->applicantProfile->registration_council,
                    'other_council' => $data['other_council'] ?? $user->applicantProfile->other_council,
                    'registration_number' => $data['registration_number'] ?? $user->applicantProfile->registration_number,
                ]);
            }

            // Update Addresses
            $addressTypes = [
                'mailing' => ['address' => 'mailing_address', 'postcode' => 'mailing_postcode', 'city' => 'mailing_city', 'state' => 'mailing_state', 'country' => 'mailing_country'],
                'primary_clinic' => ['type_field' => 'primary_clinic_type', 'clinic_name_field' => 'primary_clinic_name', 'address' => 'primary_address', 'postcode' => 'primary_postcode', 'city' => 'primary_city', 'state' => 'primary_state', 'country' => 'primary_country'],
                'secondary_clinic' => ['type_field' => 'secondary_clinic_type', 'clinic_name_field' => 'secondary_clinic_name', 'address' => 'secondary_address', 'postcode' => 'secondary_postcode', 'city' => 'secondary_city', 'state' => 'secondary_state', 'country' => 'secondary_country'],
            ];

            foreach ($addressTypes as $type => $fields) {
                $address = $user->addresses->firstWhere('type', $type);
                $addressData = [];

                if ($type === 'mailing') {
                    $addressData = [
                        'address' => $data[$fields['address']] ?? null,
                        'postcode' => $data[$fields['postcode']] ?? null,
                        'city' => $data[$fields['city']] ?? null,
                        'state' => $data[$fields['state']] ?? null,
                        'country' => $data[$fields['country']] ?? null,
                    ];
                } else {
                    $addressData = [
                        'clinic_type' => $data[$fields['type_field']] ?? null,
                        'clinic_name' => $data[$fields['clinic_name_field']] ?? null,
                        'address' => $data[$fields['address']] ?? null,
                        'postcode' => $data[$fields['postcode']] ?? null,
                        'city' => $data[$fields['city']] ?? null,
                        'state' => $data[$fields['state']] ?? null,
                        'country' => $data[$fields['country']] ?? null,
                    ];
                }

                if ($address) {
                    $address->update($addressData);
                }
            }

            // Update Qualifications
            for ($i = 1; $i <= 3; $i++) {
                $qualification = $user->qualifications->where('sequence', $i)->first();
                $institution = $data["institution_$i"] ?? null;
                $degree = $data["qualification_$i"] ?? null;
                $year = $data["year_obtained_$i"] ?? null;

                if ($qualification && $institution && $degree && $year) {
                    $qualification->update([
                        'institution' => $institution,
                        'degree_or_qualification' => $degree,
                        'year_obtained' => $year,
                    ]);
                }
            }

            // Update Healthcare Service
            if ($user->healthcareService) {
                $user->healthcareService->update([
                    'professional_indemnity_type' => $data['professional_indemnity_type'] ?? $user->healthcareService->professional_indemnity_type,
                    'employment_status' => $data['employment_status'] ?? $user->healthcareService->employment_status,
                    'specialty_area' => $data['specialty_area'] ?? $user->healthcareService->specialty_area,
                    'cover_type' => $data['cover_type'] ?? $user->healthcareService->cover_type,
                    'locum_practice_location' => $data['locum_practice_location'] ?? $user->healthcareService->locum_practice_location,
                    'service_type' => $data['service_type'] ?? $user->healthcareService->service_type,
                    'practice_area' => $data['practice_area'] ?? $user->healthcareService->practice_area,
                ]);
            }

            // Update Policy Pricing
            if ($user->policyPricing) {
                $user->policyPricing->update([
                    'policy_start_date' => $data['policy_start_date'] ?? $user->policyPricing->policy_start_date,
                    'policy_expiry_date' => $data['policy_expiry_date'] ?? $user->policyPricing->policy_expiry_date,
                    'liability_limit' => $data['liability_limit'] ?? $user->policyPricing->liability_limit,
                    'base_premium' => $data['displayBasePremium'] ?? $user->policyPricing->base_premium,
                    'gross_premium' => $data['displayGrossPremium'] ?? $user->policyPricing->gross_premium,
                    'locum_addon' => $data['displayLocumAddon'] ?? $user->policyPricing->locum_addon,
                    'sst' => $data['displaySST'] ?? $user->policyPricing->sst,
                    'stamp_duty' => $data['displayStampDuty'] ?? $user->policyPricing->stamp_duty,
                    'total_payable' => $data['displayTotalPayable'] ?? $user->policyPricing->total_payable,
                ]);
            }

            // Update Risk Management
            if ($user->riskManagement) {
                $user->riskManagement->update([
                    'medical_records' => ($data['medical_records'] ?? 'no') === 'yes',
                    'informed_consent' => ($data['informed_consent'] ?? 'no') === 'yes',
                    'adverse_incidents' => ($data['adverse_incidents'] ?? 'no') === 'yes',
                    'sterilisation_facilities' => ($data['sterilisation_facilities'] ?? 'no') === 'yes',
                ]);
            }

            // Update Insurance History
            if ($user->insuranceHistory) {
                $user->insuranceHistory->update([
                    'current_insurance' => ($data['current_insurance'] ?? 'no') === 'yes',
                    'insurer_name' => $data['insurer_name'] ?? $user->insuranceHistory->insurer_name,
                    'period_of_insurance' => $data['period_of_insurance'] ?? $user->insuranceHistory->period_of_insurance,
                    'policy_limit_myr' => $data['policy_limit_myr'] ?? $user->insuranceHistory->policy_limit_myr,
                    'excess_myr' => $data['excess_myr'] ?? $user->insuranceHistory->excess_myr,
                    'retroactive_date' => $data['retroactive_date'] ?? $user->insuranceHistory->retroactive_date,
                    'previous_claims' => ($data['previous_claims'] ?? 'no') === 'yes',
                    'claims_details' => $data['claims_details'] ?? $user->insuranceHistory->claims_details,
                ]);
            }

            // Update Claims Experience
            if ($user->claimsExperience) {
                $user->claimsExperience->update([
                    'claims_made' => ($data['claims_made'] ?? 'no') === 'yes',
                    'aware_of_errors' => ($data['aware_of_errors'] ?? 'no') === 'yes',
                    'disciplinary_action' => ($data['disciplinary_action'] ?? 'no') === 'yes',
                    'claim_date_of_claim' => $data['claim_date_of_claim'] ?? $user->claimsExperience->claim_date_of_claim,
                    'claim_notified_date' => $data['claim_notified_date'] ?? $user->claimsExperience->claim_notified_date,
                    'claim_claimant_name' => $data['claim_claimant_name'] ?? $user->claimsExperience->claim_claimant_name,
                    'claim_allegations' => $data['claim_allegations'] ?? $user->claimsExperience->claim_allegations,
                    'claim_amount_claimed' => $data['claim_amount_claimed'] ?? $user->claimsExperience->claim_amount_claimed,
                    'claim_status' => $data['claim_status'] ?? $user->claimsExperience->claim_status,
                    'claim_amounts_paid' => $data['claim_amounts_paid'] ?? $user->claimsExperience->claim_amounts_paid,
                ]);
            }

            // Update Contact
            if ($user->applicantContact) {
                $user->applicantContact->update([
                    'contact_no' => $data['contact_no'] ?? $user->applicantContact->contact_no,
                    'email_address' => $data['email_address'] ?? $user->applicantContact->email_address,
                ]);
            }

            // Update Policy Application declarations
            $policyApplication->update([
                'agree_data_protection' => ($data['agree_declaration'] ?? 'no') === 'yes',
                'agree_declaration' => ($data['agree_declaration_final'] ?? 'no') === 'yes',
                'signature_data' => $data['signature'] ?? $policyApplication->signature_data,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully!',
                'redirect_url' => route('for-your-action.show', $policyApplication->id)
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update policy application', [
                'policy_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update application. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
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
