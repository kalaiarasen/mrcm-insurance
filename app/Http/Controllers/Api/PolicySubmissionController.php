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

            // Extract applicant email and name
            $applicantEmail = $applicationData['email_address'] ?? null;
            $applicantTitle = $applicationData['title'] ?? null;
            $applicantFullName = $applicationData['full_name'] ?? null;
            // Construct full name with title
            $applicantName = $applicantTitle && $applicantFullName 
                ? strtoupper($applicantTitle) . '. ' . $applicantFullName 
                : ($applicantFullName ?? 'Applicant');
            $applicantContactNo = $applicationData['contact_no'] ?? null;
            $applicantPassword = $applicationData['password'] ?? null;
            $applicantConfirmPassword = $applicationData['confirm_password'] ?? null;

            if (!$applicantEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Applicant email address is required',
                ], 422);
            }

            // Validate password
            if (!$applicantPassword) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password is required',
                ], 422);
            }

            if ($applicantPassword !== $applicantConfirmPassword) {
                return response()->json([
                    'success' => false,
                    'message' => 'Passwords do not match',
                ], 422);
            }

            if (strlen($applicantPassword) < 8) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password must be at least 8 characters long',
                ], 422);
            }

                        // Create NEW user for applicant (not use Auth::user())
            // Check if user already exists with this email
            $currentUser = User::where('email', $applicantEmail)->first();

            if (!$currentUser) {
                // Create new user with applicant-provided password
                $currentUser = User::create([
                    'name' => $applicantName,
                    'email' => $applicantEmail,
                    'contact_no' => $applicantContactNo,
                    'password' => Hash::make($applicantPassword), // Use applicant's password
                    'email_verified_at' => now(), // Auto-verify applicant email
                    'application_status' => 'submitted',
                    'application_submitted_at' => now(),
                ]);
            } else {
                // Update existing user - update password only if new one provided
                $currentUser->update([
                    'name' => $applicantName,
                    'contact_no' => $applicantContactNo,
                    'password' => Hash::make($applicantPassword), // Update password
                    'application_status' => 'submitted',
                    'application_submitted_at' => now(),
                ]);
            }

            // Step 1: Save Applicant Profile
            // Mark old profiles as inactive
            ApplicantProfile::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active profile
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

            // Save Qualifications (up to 3)
            // Mark old qualifications as inactive
            Qualification::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active qualifications
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

            // Save Addresses (Mailing, Primary, Secondary)
            // Mark old addresses as inactive
            Address::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active addresses
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
                    Address::create(array_merge($addressData, [
                        'user_id' => $currentUser->id,
                        'type' => $type,
                        'is_used' => true,
                    ]));
                }
            }

            // Save Applicant Contact
            // Mark old contacts as inactive
            ApplicantContact::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active contact
            ApplicantContact::create([
                'user_id' => $currentUser->id,
                'contact_no' => $applicationData['contact_no'] ?? null,
                'email_address' => $applicationData['email_address'] ?? null,
                'is_used' => true,
            ]);

            // Step 2: Save Healthcare Services
            // Mark old services as inactive
            HealthcareService::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active service
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

            // Step 3: Save Policy Pricing
            // Mark old pricing as inactive
            PolicyPricing::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active pricing
            PolicyPricing::create([
                'user_id' => $currentUser->id,
                'policy_start_date' => $applicationData['policy_start_date'] ?? null,
                'policy_expiry_date' => $applicationData['policy_expiry_date'] ?? null,
                'liability_limit' => $applicationData['liability_limit'] ?? null,
                'base_premium' => $applicationData['displayBasePremium'] ?? 0,
                'gross_premium' => $applicationData['displayGrossPremium'] ?? 0,
                'locum_addon' => $applicationData['displayLocumAddon'] ?? 0,
                'sst' => $applicationData['displaySST'] ?? 0,
                'stamp_duty' => $applicationData['displayStampDuty'] ?? 10,
                'total_payable' => $applicationData['displayTotalPayable'] ?? 0,
                'is_used' => true,
            ]);

            // Step 4: Save Risk Management
            // Mark old risk management as inactive
            RiskManagement::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active risk management
            RiskManagement::create([
                'user_id' => $currentUser->id,
                'medical_records' => $applicationData['medical_records'] === 'yes',
                'informed_consent' => $applicationData['informed_consent'] === 'yes',
                'adverse_incidents' => $applicationData['adverse_incidents'] === 'yes',
                'sterilisation_facilities' => $applicationData['sterilisation_facilities'] === 'yes',
                'is_used' => true,
            ]);

            // Step 5: Save Insurance History
            // Mark old insurance history as inactive
            InsuranceHistory::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active insurance history
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

            // Step 6: Save Claims Experience
            // Mark old claims experience as inactive
            ClaimsExperience::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active claims experience
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

            // Step 7 & 8: Save Policy Application (Declaration & Signature)
            // Mark old policy applications as inactive
            PolicyApplication::where('user_id', $currentUser->id)->update(['is_used' => false]);
            // Create new active policy application (reference number will be assigned after approval)
            PolicyApplication::create([
                'user_id' => $currentUser->id,
                'agree_data_protection' => $applicationData['agree_declaration'] === 'yes',
                'agree_declaration' => $applicationData['agree_declaration_final'] === 'yes',
                'signature_data' => $applicationData['signature'] ?? null,
                'status' => 'submitted',
                'submitted_at' => now(),
                'is_used' => true,
            ]);

            // Assign client role if not already assigned
            if (!$currentUser->hasRole('client')) {
                $currentUser->assignRole('client');
            }

            // Increment submission version
            $currentUser->submission_version = ($currentUser->submission_version ?? 0) + 1;
            $currentUser->save();

            DB::commit();

            // Log the submission
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
     * 
     * @param int $userId
     * @return string
     */
    private function generateReferenceNumber($userId)
    {
        return 'POL-' . date('Ymd') . '-' . str_pad($userId, 6, '0', STR_PAD_LEFT);
    }
}
