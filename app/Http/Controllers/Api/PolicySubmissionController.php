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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PolicySubmissionController extends Controller
{
    /**
     * Submit the complete insurance policy application
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $validator = Validator::make($request->all(), [
                'application_data' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $applicationData = $request->input('application_data');
            $currentUser = Auth::user();

            if (!$currentUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                ], 401);
            }

            // Create or update user with authentication credentials
            if (isset($applicationData['email_address']) && $applicationData['email_address'] !== $currentUser->email) {
                // Update user email if different
                $currentUser->email = $applicationData['email_address'];
            }

            // Update contact number if provided
            if (isset($applicationData['contact_no'])) {
                $currentUser->contact_no = $applicationData['contact_no'];
            }

            // Mark application as submitted
            $currentUser->application_status = 'submitted';
            $currentUser->application_submitted_at = now();
            $currentUser->save();

            // Step 1: Save Applicant Profile
            $applicantProfile = ApplicantProfile::firstOrCreate(
                ['user_id' => $currentUser->id],
                [
                    'title' => $applicationData['title'] ?? null,
                    'nationality_status' => $applicationData['nationality_status'] ?? null,
                    'nric_number' => $applicationData['nric_number'] ?? null,
                    'passport_number' => $applicationData['passport_number'] ?? null,
                    'gender' => $applicationData['gender'] ?? null,
                    'registration_council' => $applicationData['registration_council'] ?? null,
                    'other_council' => $applicationData['other_council'] ?? null,
                    'registration_number' => $applicationData['registration_number'] ?? null,
                ]
            );

            // Save Qualifications (up to 3)
            for ($i = 1; $i <= 3; $i++) {
                $institution = $applicationData["institution_$i"] ?? null;
                $qualification = $applicationData["qualification_$i"] ?? null;
                $yearObtained = $applicationData["year_obtained_$i"] ?? null;

                if ($institution && $qualification && $yearObtained) {
                    Qualification::updateOrCreate(
                        ['user_id' => $currentUser->id, 'sequence' => $i],
                        [
                            'institution' => $institution,
                            'degree_or_qualification' => $qualification,
                            'year_obtained' => $yearObtained,
                        ]
                    );
                }
            }

            // Save Addresses (Mailing, Primary, Secondary)
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
                    // Clinic address
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
                    Address::updateOrCreate(
                        ['user_id' => $currentUser->id, 'type' => $type],
                        $addressData
                    );
                }
            }

            // Save Applicant Contact
            ApplicantContact::updateOrCreate(
                ['user_id' => $currentUser->id],
                [
                    'contact_no' => $applicationData['contact_no'] ?? null,
                    'email_address' => $applicationData['email_address'] ?? null,
                ]
            );

            // Step 2: Save Healthcare Services
            HealthcareService::updateOrCreate(
                ['user_id' => $currentUser->id],
                [
                    'professional_indemnity_type' => $applicationData['professional_indemnity_type'] ?? null,
                    'employment_status' => $applicationData['employment_status'] ?? null,
                    'specialty_area' => $applicationData['specialty_area'] ?? null,
                    'cover_type' => $applicationData['cover_type'] ?? null,
                    'locum_practice_location' => $applicationData['locum_practice_location'] ?? null,
                    'service_type' => $applicationData['service_type'] ?? null,
                    'practice_area' => $applicationData['practice_area'] ?? null,
                ]
            );

            // Step 3: Save Policy Pricing
            PolicyPricing::updateOrCreate(
                ['user_id' => $currentUser->id],
                [
                    'policy_start_date' => $applicationData['policy_start_date'] ?? null,
                    'policy_expiry_date' => $applicationData['policy_expiry_date'] ?? null,
                    'liability_limit' => $applicationData['liability_limit'] ?? null,
                    'base_premium' => $applicationData['displayBasePremium'] ?? 0,
                    'gross_premium' => $applicationData['displayGrossPremium'] ?? 0,
                    'locum_addon' => $applicationData['displayLocumAddon'] ?? 0,
                    'sst' => $applicationData['displaySST'] ?? 0,
                    'stamp_duty' => $applicationData['displayStampDuty'] ?? 10,
                    'total_payable' => $applicationData['displayTotalPayable'] ?? 0,
                ]
            );

            // Step 4: Save Risk Management
            RiskManagement::updateOrCreate(
                ['user_id' => $currentUser->id],
                [
                    'medical_records' => $applicationData['medical_records'] === 'yes',
                    'informed_consent' => $applicationData['informed_consent'] === 'yes',
                    'adverse_incidents' => $applicationData['adverse_incidents'] === 'yes',
                    'sterilisation_facilities' => $applicationData['sterilisation_facilities'] === 'yes',
                ]
            );

            // Step 5: Save Insurance History
            InsuranceHistory::updateOrCreate(
                ['user_id' => $currentUser->id],
                [
                    'current_insurance' => $applicationData['current_insurance'] === 'yes',
                    'insurer_name' => $applicationData['insurer_name'] ?? null,
                    'period_of_insurance' => $applicationData['period_of_insurance'] ?? null,
                    'policy_limit_myr' => $applicationData['policy_limit_myr'] ?? null,
                    'excess_myr' => $applicationData['excess_myr'] ?? null,
                    'retroactive_date' => $applicationData['retroactive_date'] ?? null,
                    'previous_claims' => $applicationData['previous_claims'] === 'yes',
                    'claims_details' => $applicationData['claims_details'] ?? null,
                ]
            );

            // Step 6: Save Claims Experience
            ClaimsExperience::updateOrCreate(
                ['user_id' => $currentUser->id],
                [
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
                ]
            );

            // Step 7 & 8: Save Policy Application (Declaration & Signature)
            PolicyApplication::updateOrCreate(
                ['user_id' => $currentUser->id],
                [
                    'agree_data_protection' => $applicationData['agree_declaration'] === 'yes',
                    'agree_declaration' => $applicationData['agree_declaration_final'] === 'yes',
                    'signature_data' => $applicationData['signature'] ?? null,
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]
            );

            // Assign client role if not already assigned
            if (!$currentUser->hasRole('client')) {
                $currentUser->assignRole('client');
            }

            DB::commit();

            // Log the submission
            \Log::info('Policy application submitted', [
                'user_id' => $currentUser->id,
                'submitted_at' => now(),
                'reference_number' => $this->generateReferenceNumber($currentUser->id),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully',
                'reference_number' => $this->generateReferenceNumber($currentUser->id),
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
     * 
     * @param int $userId
     * @return string
     */
    private function generateReferenceNumber($userId)
    {
        return 'POL-' . date('Ymd') . '-' . str_pad($userId, 6, '0', STR_PAD_LEFT);
    }
}
