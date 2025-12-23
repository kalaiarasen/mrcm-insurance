<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApplicantProfile;
use App\Models\Qualification;
use App\Models\Address;
use App\Models\ApplicantContact;
use App\Models\HealthcareService;
use App\Models\PolicyPricing;
use App\Models\RiskManagement;
use App\Models\InsuranceHistory;
use App\Models\ClaimsExperience;
use App\Models\PolicyApplication;
use App\Models\AgentCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PolicySubmissionController extends Controller
{
    public function submit(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'application_data' => 'required|array',
                'rejected_policy_id' => 'nullable|integer|exists:policy_applications,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $applicationData = $request->input('application_data');
            $rejectedPolicyId = $request->input('rejected_policy_id');

            $applicantTitle = $applicationData['title'] ?? null;
            $applicantFullName = $applicationData['full_name'] ?? null;
            $applicantName = $applicantTitle && $applicantFullName 
                ? strtoupper($applicantTitle) . '. ' . $applicantFullName 
                : ($applicantFullName ?? 'Applicant');
            $applicantContactNo = $applicationData['contact_no'] ?? null;

            // Get the authenticated user
            $currentUser = Auth::user();

            if (!$currentUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'User must be logged in to submit an application',
                ], 401);
            }

            // Check if editing a rejected policy
            $isEditingRejectedPolicy = false;
            $existingPolicyApplication = null;
            
            if ($rejectedPolicyId) {
                $existingPolicyApplication = PolicyApplication::where('id', $rejectedPolicyId)
                    ->where('user_id', $currentUser->id)
                    ->where('status', 'rejected')
                    ->first();
                    
                if (!$existingPolicyApplication) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Rejected policy not found or cannot be edited',
                    ], 404);
                }
                
                $isEditingRejectedPolicy = true;
            }

            // Update the user's information
            $currentUser->update([
                'name' => $applicantName,
                'contact_no' => $applicantContactNo,
                'application_status' => 'submitted',
                'application_submitted_at' => now(),
            ]);

            ApplicantProfile::where('user_id', $currentUser->id)->update(['is_used' => false]);
            $applicantProfile = ApplicantProfile::create([
                'user_id' => $currentUser->id,
                'title' => $applicationData['title'] ?? null,
                'nationality_status' => $applicationData['nationality_status'] ?? null,
                'nric_number' => $applicationData['nric_number'] ?? null,
                'passport_number' => $applicationData['passport_number'] ?? null,
                'gender' => $applicationData['gender'] ?? null,
                'registration_council' => $applicationData['registration_council'] ?? null,
                'other_council' => $applicationData['other_council'] ?? null,
                'registration_number' => $applicationData['registration_number'] ?? null,
                'is_used' => true,
            ]);

            Qualification::where('user_id', $currentUser->id)->update(['is_used' => false]);
            for ($i = 1; $i <= 3; $i++) {
                $institution = $applicationData["institution_$i"] ?? null;
                $qualification = $applicationData["qualification_$i"] ?? null;
                $yearObtained = $applicationData["year_obtained_$i"] ?? null;

                if ($institution && $qualification && $yearObtained) {
                    Qualification::create([
                        'user_id' => $currentUser->id,
                        'sequence' => $i,
                        'institution' => $institution,
                        'degree_or_qualification' => $qualification,
                        'year_obtained' => $yearObtained,
                        'is_used' => true,
                    ]);
                }
            }

            Address::where('user_id', $currentUser->id)->update(['is_used' => false]);
            $addressTypes = [
                'mailing' => ['address' => 'mailing_address', 'postcode' => 'mailing_postcode', 'city' => 'mailing_city', 'state' => 'mailing_state', 'country' => 'mailing_country'],
                'primary_clinic' => ['type_field' => 'primary_clinic_type', 'clinic_name_field' => 'primary_clinic_name', 'address' => 'primary_address', 'postcode' => 'primary_postcode', 'city' => 'primary_city', 'state' => 'primary_state', 'country' => 'primary_country'],
                'secondary_clinic' => ['type_field' => 'secondary_clinic_type', 'clinic_name_field' => 'secondary_clinic_name', 'address' => 'secondary_address', 'postcode' => 'secondary_postcode', 'city' => 'secondary_city', 'state' => 'secondary_state', 'country' => 'secondary_country'],
            ];

            foreach ($addressTypes as $type => $fields) {
                $addressData = [];

                if ($type === 'mailing') {
                    $addressData = [
                        'address' => $applicationData[$fields['address']] ?? null,
                        'postcode' => $applicationData[$fields['postcode']] ?? null,
                        'city' => $applicationData[$fields['city']] ?? null,
                        'state' => $applicationData[$fields['state']] ?? null,
                        'country' => $applicationData[$fields['country']] ?? null,
                    ];
                } else {
                    $addressData = [
                        'clinic_type' => $applicationData[$fields['type_field']] ?? null,
                        'clinic_name' => $applicationData[$fields['clinic_name_field']] ?? null,
                        'address' => $applicationData[$fields['address']] ?? null,
                        'postcode' => $applicationData[$fields['postcode']] ?? null,
                        'city' => $applicationData[$fields['city']] ?? null,
                        'state' => $applicationData[$fields['state']] ?? null,
                        'country' => $applicationData[$fields['country']] ?? null,
                    ];
                }

                if (!empty(array_filter($addressData))) {
                    Address::create(array_merge($addressData, [
                        'user_id' => $currentUser->id,
                        'type' => $type,
                        'is_used' => true,
                    ]));
                }
            }

            ApplicantContact::where('user_id', $currentUser->id)->update(['is_used' => false]);
            ApplicantContact::create([
                'user_id' => $currentUser->id,
                'contact_no' => $applicationData['contact_no'] ?? null,
                'email_address' => $applicationData['email_address'] ?? null,
                'is_used' => true,
            ]);

            HealthcareService::where('user_id', $currentUser->id)->update(['is_used' => false]);
            HealthcareService::create([
                'user_id' => $currentUser->id,
                'professional_indemnity_type' => $applicationData['professional_indemnity_type'] ?? null,
                'employment_status' => $applicationData['employment_status'] ?? null,
                'specialty_area' => $applicationData['specialty_area'] ?? null,
                'cover_type' => $applicationData['cover_type'] ?? null,
                'locum_practice_location' => $applicationData['locum_practice_location'] ?? null,
                'service_type' => $applicationData['service_type'] ?? null,
                'practice_area' => $applicationData['practice_area'] ?? null,
                'is_used' => true,
            ]);

            PolicyPricing::where('user_id', $currentUser->id)->update(['is_used' => false]);
            
            $policyPricing = PolicyPricing::create([
                'user_id' => $currentUser->id,
                'policy_start_date' => $applicationData['policy_start_date'] ?? null,
                'policy_expiry_date' => $applicationData['policy_expiry_date'] ?? null,
                'liability_limit' => $applicationData['liability_limit'] ?? null,
                'base_premium' => $applicationData['displayBasePremium'] ?? 0,
                'loading_percentage' => $applicationData['displayLoadingPercentage'] ?? 0,
                'loading_amount' => $applicationData['displayLoadingAmount'] ?? 0,
                'gross_premium' => $applicationData['displayGrossPremium'] ?? 0,
                'locum_addon' => $applicationData['displayLocumAddon'] ?? 0,
                'locum_extension' => $applicationData['locum_extension'] ?? false,
                'discount_percentage' => $applicationData['displayDiscountPercentage'] ?? 0,
                'discount_amount' => $applicationData['displayDiscountAmount'] ?? 0,
                'voucher_code' => $applicationData['voucher_code'] ?? null,
                'sst' => $applicationData['displaySST'] ?? 0,
                'stamp_duty' => $applicationData['displayStampDuty'] ?? 10,
                'total_payable' => $applicationData['displayTotalPayable'] ?? 0,
                'is_used' => true,
            ]);

            RiskManagement::where('user_id', $currentUser->id)->update(['is_used' => false]);
            RiskManagement::create([
                'user_id' => $currentUser->id,
                'medical_records' => $applicationData['medical_records'] === 'yes',
                'informed_consent' => $applicationData['informed_consent'] === 'yes',
                'adverse_incidents' => $applicationData['adverse_incidents'] === 'yes',
                'sterilisation_facilities' => $applicationData['sterilisation_facilities'] === 'yes',
                'is_used' => true,
            ]);

            InsuranceHistory::where('user_id', $currentUser->id)->update(['is_used' => false]);
            InsuranceHistory::create([
                'user_id' => $currentUser->id,
                'current_insurance' => $applicationData['current_insurance'] === 'yes',
                'insurer_name' => $applicationData['insurer_name'] ?? null,
                'period_of_insurance' => $applicationData['period_of_insurance'] ?? null,
                'policy_limit_myr' => $applicationData['policy_limit_myr'] ?? null,
                'excess_myr' => $applicationData['excess_myr'] ?? null,
                'retroactive_date' => $applicationData['retroactive_date'] ?? null,
                'previous_claims' => $applicationData['previous_claims'] === 'yes',
                'claims_details' => $applicationData['claims_details'] ?? null,
                'is_used' => true,
            ]);

            ClaimsExperience::where('user_id', $currentUser->id)->update(['is_used' => false]);
            ClaimsExperience::create([
                'user_id' => $currentUser->id,
                'claims_made' => $applicationData['claims_made'] === 'yes',
                'aware_of_errors' => $applicationData['aware_of_errors'] === 'yes',
                'disciplinary_action' => $applicationData['disciplinary_action'] === 'yes',
                'claim_date_of_claim' => $applicationData['claim_date_of_claim'] ?? null,
                'claim_notified_date' => $applicationData['claim_notified_date'] ?? null,
                'claim_claimant_name' => $applicationData['claim_claimant_name'] ?? null,
                'claim_allegations' => $applicationData['claim_allegations'] ?? null,
                'claim_amount_claimed' => $applicationData['claim_amount_claimed'] ?? null,
                'claim_status' => $applicationData['claim_status'] ?? null,
                'claim_amounts_paid' => $applicationData['claim_amounts_paid'] ?? null,
                'is_used' => true,
            ]);

            // Update existing rejected policy or create new one
            if ($isEditingRejectedPolicy) {
                // Update existing rejected policy
                $existingPolicyApplication->update([
                    'agree_data_protection' => $applicationData['agree_declaration'] === 'yes',
                    'agree_declaration' => $applicationData['agree_declaration_final'] === 'yes',
                    'signature_data' => $applicationData['signature'] ?? null,
                    'status' => 'submitted',
                    'customer_status' => 'submitted',  // C.S: submitted
                    'admin_status' => 'new_case',      // A.S: New case
                    'submitted_at' => now(),
                    'remarks' => null,  // Clear rejection reason
                ]);
                
                $policyApplication = $existingPolicyApplication;
                
                Log::info('Rejected policy resubmitted', [
                    'policy_id' => $policyApplication->id,
                    'user_id' => $currentUser->id,
                ]);
            } else {
                // Create new policy application
                // PolicyApplication::where('user_id', $currentUser->id)->update(['is_used' => false]);
                $policyApplication = PolicyApplication::create([
                    'user_id' => $currentUser->id,
                    'agree_data_protection' => $applicationData['agree_declaration'] === 'yes',
                    'agree_declaration' => $applicationData['agree_declaration_final'] === 'yes',
                    'signature_data' => $applicationData['signature'] ?? null,
                    'status' => 'submitted',
                    'customer_status' => 'submitted',  // C.S: submitted
                    'admin_status' => 'new_case',      // A.S: New case (or new_renewal for renewals)
                    'submitted_at' => now(),
                    'is_used' => true,
                ]);
            }

            // Link the pricing to the application
            $policyPricing->update(['policy_application_id' => $policyApplication->id]);

            // Calculate and create agent commission if client was referred by an agent
            // Update existing commission if policy pricing changed
            if ($currentUser->agent_id) {
                $agent = User::find($currentUser->agent_id);
                
                if ($agent && $agent->commission_percentage > 0) {
                    // Commission base is base premium + locum addon (excluding loading)
                    $basePremium = floatval($policyPricing->base_premium);
                    $locumAddon = floatval($policyPricing->locum_addon);
                    $commissionBase = $basePremium + $locumAddon;
                    
                    // Calculate commission amount
                    $commissionAmount = $commissionBase * (floatval($agent->commission_percentage) / 100);
                    
                    // Check if commission already exists for this policy
                    $existingCommission = AgentCommission::where('policy_application_id', $policyApplication->id)->first();
                    
                    if ($existingCommission) {
                        // Update existing commission (in case pricing changed)
                        $existingCommission->update([
                            'commission_rate' => $agent->commission_percentage,
                            'base_amount' => $commissionBase,
                            'commission_amount' => $commissionAmount,
                        ]);

                        Log::info('Agent commission updated', [
                            'commission_id' => $existingCommission->id,
                            'agent_id' => $agent->id,
                            'policy_application_id' => $policyApplication->id,
                            'commission_amount' => $commissionAmount,
                        ]);
                    } else {
                        // Create new commission record
                        AgentCommission::create([
                            'agent_id' => $agent->id,
                            'policy_application_id' => $policyApplication->id,
                            'client_id' => $currentUser->id,
                            'commission_rate' => $agent->commission_percentage,
                            'base_amount' => $commissionBase,
                            'commission_amount' => $commissionAmount,
                        ]);

                        Log::info('Agent commission created', [
                            'agent_id' => $agent->id,
                            'client_id' => $currentUser->id,
                            'policy_application_id' => $policyApplication->id,
                            'commission_amount' => $commissionAmount,
                        ]);
                    }
                }
            }

            if (!$currentUser->hasRole('client')) {
                $currentUser->assignRole('client');
            }

            $currentUser->submission_version = ($currentUser->submission_version ?? 0) + 1;
            $currentUser->save();

            DB::commit();

            \Log::info('Policy application submitted', [
                'user_id' => $currentUser->id,
                'submitted_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully',
                'user_id' => $currentUser->id,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Policy submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your application. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate a unique reference number for the application
     * Format: MRCM#YY-XXXX where YY is policy year (e.g., 26 for 2025-2026 policy)
     * 
     * @param int $userId
     * @return string
     */
    private function generateReferenceNumber($userId)
    {
        // Get the last 2 digits of current year + 1 for policy year
        // For 2025, policy year is 2025-2026, so we use 26
        $policyYear = substr((string)(date('Y') + 1), -2);
        
        return 'MRCM#' . $policyYear . '-' . str_pad($userId, 4, '0', STR_PAD_LEFT);
    }
}
