<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\PolicyApplication;

class TBLPolicyMSTSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/TBL_Policy_MST.csv');

        if (!file_exists($path)) {
            $this->command->error("TBL_Policy_MST.csv not found in storage/app/");
            return;
        }

        if (($handle = fopen($path, 'r')) === false) {
            $this->command->error("Cannot open TBL_Policy_MST.csv");
            return;
        }

        $header = fgetcsv($handle); // skip header row

        DB::beginTransaction();

        try {
            $count = 0;
            while (($row = fgetcsv($handle)) !== false) {

                $memberId = trim($row[7] ?? ''); // numMemberID

                if (!$memberId) {
                    $this->command->warn("numMemberID missing. Skipping row.");
                    continue;
                }

                // -------------------------
                // 1. FIND USER
                // -------------------------
                $user = User::where('old_member_id', $memberId)->first();

                if (!$user) {
                    $this->command->warn("User with ID {$memberId} not found. Skipping row.");
                    continue;
                }

                // -------------------------
                // 2. INSERT/UPDATE healthcare_services
                // -------------------------
                // Map CSV values to form field values
                $professionalIndemnityType = $this->mapProfessionalIndemnityType($row[8] ?? '');
                $employmentStatus = $this->mapEmploymentStatus($row[12] ?? '');
                $specialtyArea = $this->mapSpecialtyArea($row[10] ?? '', $row[11] ?? '');
                $coverType = $this->mapCoverType($row[13] ?? '');
                $serviceType = $this->mapServiceType($row[15] ?? '');
                $practiceArea = $this->mapPracticeArea($row[14] ?? '', $row[15] ?? '');
                $locumPracticeLocation = $this->mapLocumPracticeLocation($row[15] ?? '');
                
                DB::table('healthcare_services')->insert(
                    [
                        'user_id'                   => $user->id,
                        'professional_indemnity_type' => $professionalIndemnityType,
                        'employment_status'           => $employmentStatus,
                        'specialty_area'              => $specialtyArea,
                        'cover_type'                  => $coverType,
                        'service_type'                => $serviceType,
                        'practice_area'               => $practiceArea,
                        'locum_practice_location'     => $locumPracticeLocation,
                        'is_used'                     => 1,
                        'created_at'                  => $this->toDateTime($row[27] ?? null),
                        'updated_at'                  => $this->toDateTime($row[28] ?? null),
                    ]
                );

                // -------------------------
                // 3. INSERT/UPDATE policy_pricings
                // -------------------------
                DB::table('policy_pricings')->insert(
                    [
                        'user_id'                   => $user->id,
                        'liability_limit'      => $this->toNumeric($row[16] ?? 0),
                        'gross_premium'        => $this->toNumeric($row[17] ?? 0),
                        'locum_addon'          => $this->toNumeric($row[24] ?? 0), // Amount (e.g., 500.00)
                        'policy_start_date'    => $this->toDate($row[19] ?? null),
                        'policy_expiry_date'   => $this->toDate($row[20] ?? null),
                        'locum_extension'      => $this->toBool($row[21] ?? 0), // Boolean (0 or 1)
                        'sst'                  => $this->toNumeric($row[22] ?? 0),
                        'total_payable'        => $this->toNumeric($row[25] ?? 0),
                        'is_used'              => 1,
                        'created_at'           => $this->toDateTime($row[27] ?? null),
                        'updated_at'           => $this->toDateTime($row[28] ?? null),
                    ]
                );

                // -------------------------
                // 4. INSERT/UPDATE policy_applications
                // -------------------------
                $oldPolicyId = trim($row[0] ?? '');
                $oldPolicyUuid = trim($row[2] ?? '');
                $referenceNumber = trim($row[3] ?? '');
                
                // Generate reference number if missing (to avoid duplicate NULL in unique column)
                // Format: MRCM#YY-XXXX (matching PolicySubmissionController format)
                if (empty($referenceNumber)) {
                    $policyYear = substr((string)(date('Y') + 1), -2);
                    $referenceNumber = 'MRCM#' . $policyYear . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
                    $this->command->info("Generated reference number: {$referenceNumber} for user {$user->id}");
                }
                
                // Final safety check - should never be empty
                if (empty($referenceNumber)) {
                    $referenceNumber = 'POLICY-' . $oldPolicyId;
                    $this->command->warn("Using fallback reference: {$referenceNumber}");
                }
                
                $status = (int)($row[26] ?? 0);
                
                // Map old status to new dual status system
                $statusMapping = $this->mapStatus($status, $referenceNumber);
                
                // Parse card expiry date (format: "2027-10-01 00:00:00.000")
                $cardExpiry = $this->toDate($row[37] ?? null);
                $expiryMonth = null;
                $expiryYear = null;
                if ($cardExpiry && $cardExpiry !== '1900-01-01') {
                    $expiryMonth = date('m', strtotime($cardExpiry));
                    $expiryYear = date('Y', strtotime($cardExpiry));
                }
                
                // Map payment method (ENUM: 'proof', 'credit_card')
                $paymentMethod = trim($row[31] ?? '');
                if ($paymentMethod && !in_array($paymentMethod, ['proof', 'credit_card'])) {
                    // Try to map common variations
                    $paymentMethod = strtolower($paymentMethod);
                    if (str_contains($paymentMethod, 'credit') || str_contains($paymentMethod, 'card')) {
                        $paymentMethod = 'credit_card';
                    } elseif (str_contains($paymentMethod, 'proof') || str_contains($paymentMethod, 'transfer')) {
                        $paymentMethod = 'proof';
                    } else {
                        $paymentMethod = null; // Set to null if can't map
                    }
                }
                
                $policyData = [
                    'old_policy_uuid'       => $oldPolicyUuid ?: null,
                    'reference_number'      => $referenceNumber, // Always has a value (generated if empty)
                    'customer_status'       => $statusMapping['customer_status'],
                    'admin_status'          => $statusMapping['admin_status'],
                    'status'                => $statusMapping['status'],
                    'agree_data_protection' => true,
                    'agree_declaration'     => true,
                    'remarks'               => trim($row[40] ?? '') ?: null,
                    'policy_schedule_path'  => trim($row[29] ?? '') ?: null,
                    'payment_document'      => trim($row[30] ?? '') ?: null,
                    'submitted_at'          => $statusMapping['submitted_at'],
                    'approved_at'           => $statusMapping['approved_at'],
                    'payment_received_at'   => $statusMapping['payment_received_at'],
                    'created_at'            => $this->toDateTime($row[27] ?? null),
                    'updated_at'            => $this->toDateTime($row[28] ?? null),
                ];
                
                // Only add payment fields if payment_method is set
                if ($paymentMethod) {
                    $policyData['payment_method'] = $paymentMethod;
                    $policyData['name_on_card'] = trim($row[32] ?? '') ?: null;
                    $policyData['nric_no'] = trim($row[33] ?? '') ?: null;
                    $policyData['card_no'] = trim($row[34] ?? '') ?: null;
                    
                    // Map card type to array format (e.g., ["visa"] or ["master"])
                    $cardTypeRaw = trim($row[36] ?? '');
                    if ($cardTypeRaw) {
                        $cardTypeLower = strtolower($cardTypeRaw);
                        if (str_contains($cardTypeLower, 'visa')) {
                            $policyData['card_type'] = json_encode(['visa']);
                        } elseif (str_contains($cardTypeLower, 'master')) {
                            $policyData['card_type'] = json_encode(['master']);
                        } else {
                            $policyData['card_type'] = json_encode([strtolower($cardTypeRaw)]);
                        }
                    } else {
                        $policyData['card_type'] = null;
                    }
                    
                    $policyData['expiry_month'] = $expiryMonth;
                    $policyData['expiry_year'] = $expiryYear;
                    $policyData['card_issuing_bank'] = trim($row[38] ?? '') ?: null;
                    
                    // Map relationship to array format (e.g., ["self"] or ["family_members"])
                    $relationshipRaw = trim($row[39] ?? '');
                    if ($relationshipRaw) {
                        $relationshipLower = strtolower($relationshipRaw);
                        if (str_contains($relationshipLower, 'self')) {
                            $policyData['relationship'] = json_encode(['self']);
                        } elseif (str_contains($relationshipLower, 'family') || str_contains($relationshipLower, 'member')) {
                            $policyData['relationship'] = json_encode(['family_members']);
                        } elseif (str_contains($relationshipLower, 'other')) {
                            $policyData['relationship'] = json_encode(['others']);
                        } else {
                            $policyData['relationship'] = json_encode(['self']);
                        }
                    } else {
                        $policyData['relationship'] = null;
                    }
                }
                
                DB::table('policy_applications')->updateOrInsert(
                    ['user_id' => $user->id, 'old_policy_id' => $oldPolicyId],
                    $policyData
                );

                $count++;
                if ($count % 100 == 0) {
                    $this->command->info("Processed {$count} policies...");
                }
            }

            DB::commit();
            fclose($handle);
            $this->command->info("TBL_Policy_MST.csv has been successfully imported! Total: {$count} policies");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->command->error("Seeder failed: " . $e->getMessage());
            $this->command->error("Stack trace: " . $e->getTraceAsString());
        }
    }

    /**
     * Map old status codes to new dual status system
     */
    private function mapStatus($oldStatus, $referenceNumber)
    {
        $now = now();
        
        switch ($oldStatus) {
            case 0: // New/Draft
                return [
                    'status' => 'submitted',
                    'customer_status' => 'submitted',
                    'admin_status' => 'new_case',
                    'submitted_at' => $now,
                    'approved_at' => null,
                    'payment_received_at' => null,
                ];
                
            case 1: // Active (approved)
                return [
                    'status' => 'active',
                    'customer_status' => 'active',
                    'admin_status' => 'active',
                    'submitted_at' => $now,
                    'approved_at' => $now,
                    'payment_received_at' => $now,
                ];
                
            case 2: // Pending review
                return [
                    'status' => 'submitted',
                    'customer_status' => 'submitted',
                    'admin_status' => 'new_case',
                    'submitted_at' => $now,
                    'approved_at' => null,
                    'payment_received_at' => null,
                ];
                
            case 3: // Approved/Paid
                return [
                    'status' => 'paid',
                    'customer_status' => 'paid',
                    'admin_status' => 'paid',
                    'submitted_at' => $now,
                    'approved_at' => $now,
                    'payment_received_at' => $now,
                ];
                
            case 5: // Active with reference number
                return [
                    'status' => 'active',
                    'customer_status' => 'active',
                    'admin_status' => 'active',
                    'submitted_at' => $now,
                    'approved_at' => $now,
                    'payment_received_at' => $now,
                ];
                
            case 6: // Cancelled/Rejected
                return [
                    'status' => 'rejected',
                    'customer_status' => 'rejected',
                    'admin_status' => 'rejected',
                    'submitted_at' => $now,
                    'approved_at' => null,
                    'payment_received_at' => null,
                ];
                
            default: // Unknown status, treat as submitted
                return [
                    'status' => 'submitted',
                    'customer_status' => 'submitted',
                    'admin_status' => 'new_case',
                    'submitted_at' => $now,
                    'approved_at' => null,
                    'payment_received_at' => null,
                ];
        }
    }

    private function toBool($value)
    {
        $v = strtolower(trim($value));
        return ($v === 'yes' || $v === '1' || $v === 'true') ? 1 : 0;
    }

    private function toNumeric($value)
    {
        $value = str_replace([',', '$', ' '], '', $value);
        return is_numeric($value) ? (float)$value : 0;
    }

    private function toDate($value)
    {
        if (!$value) return null;
        
        // Skip default dates
        if (str_contains($value, '1900-01-01')) {
            return null;
        }
        
        try {
            $date = date('Y-m-d', strtotime($value));
            return $date !== '1900-01-01' ? $date : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function toDateTime($value)
    {
        if (!$value) return now();
        
        // Skip default dates
        if (str_contains($value, '1900-01-01')) {
            return now();
        }
        
        try {
            return date('Y-m-d H:i:s', strtotime($value));
        } catch (\Exception $e) {
            return now();
        }
    }

    /**
     * Map CSV professional indemnity type to form values
     * CSV: "Medical", "Dental Practitioner", "Pharmacist"
     * Form: "medical_practice", "dental_practice", "pharmacist"
     */
    private function mapProfessionalIndemnityType($csvValue)
    {
        $value = strtolower(trim($csvValue));
        
        if (str_contains($value, 'medical')) {
            return 'medical_practice';
        } elseif (str_contains($value, 'dental')) {
            return 'dental_practice';
        } elseif (str_contains($value, 'pharmacist')) {
            return 'pharmacist';
        }
        
        return '';
    }

    /**
     * Map CSV employment status to form values
     * CSV: "Private", "Government", "Self-Employed"
     * Form: "private", "government", "self_employed"
     */
    private function mapEmploymentStatus($csvValue)
    {
        $value = strtolower(trim($csvValue));
        
        if (str_contains($value, 'government')) {
            return 'government';
        } elseif (str_contains($value, 'private')) {
            return 'private';
        } elseif (str_contains($value, 'self')) {
            return 'self_employed';
        }
        
        return 'private'; // default
    }

    /**
     * Map CSV specialty area to form values
     * CSV: "General Practitioner", "Medical Specialist", "General Dentist"
     * Form: "general_practice", "specialist", "dental", "dental_specialist"
     */
    private function mapSpecialtyArea($medicalStatus, $specialty)
    {
        $status = strtolower(trim($medicalStatus));
        $spec = strtolower(trim($specialty));
        
        // Medical Officer
        if (str_contains($status, 'medical officer') || str_contains($spec, 'medical officer')) {
            return 'medical_officer';
        }
        
        // General Practitioner
        if (str_contains($status, 'general practitioner') || str_contains($spec, 'general practitioner')) {
            return 'general_practice';
        }
        
        // Medical Specialist
        if (str_contains($status, 'medical specialist') || str_contains($spec, 'specialist')) {
            return 'specialist';
        }
        
        // Dental Specialist
        if (str_contains($status, 'dental specialist') || str_contains($spec, 'dental specialist')) {
            return 'dental_specialist';
        }
        
        // General Dentist
        if (str_contains($status, 'dental') || str_contains($spec, 'dentist')) {
            return 'dental';
        }
        
        return 'general_practice'; // default
    }

    /**
     * Map CSV cover type to form values
     * CSV: "General Cover", "Locum cover only"
     * Form: "basic_coverage", "comprehensive_coverage", "premium_coverage"
     */
    private function mapCoverType($csvValue)
    {
        $value = strtolower(trim($csvValue));
        
        if (str_contains($value, 'locum')) {
            return 'basic_coverage';
        } elseif (str_contains($value, 'general')) {
            return 'comprehensive_coverage';
        } elseif (str_contains($value, 'premium')) {
            return 'premium_coverage';
        }
        
        return 'comprehensive_coverage'; // default
    }

    /**
     * Map CSV service type to form values
     * CSV: "Core Services with procedures", "General Dental Practice", etc.
     * Form: "core_services", "core_services_with_procedures", etc.
     */
    private function mapServiceType($csvValue)
    {
        $value = strtolower(trim($csvValue));
        
        // Core Services variations
        if (str_contains($value, 'core services with procedures')) {
            return 'core_services_with_procedures';
        } elseif (str_contains($value, 'core services')) {
            return 'core_services';
        }
        
        // GP variations
        if (str_contains($value, 'general practitioner') && str_contains($value, 'outpatient')) {
            return 'general_practitioner_private_hospital_outpatient';
        } elseif (str_contains($value, 'general practitioner') && str_contains($value, 'emergency')) {
            return 'general_practitioner_private_hospital_emergency';
        } elseif (str_contains($value, 'obstetrics')) {
            return 'general_practitioner_with_obstetrics';
        }
        
        // Cosmetic variations
        if (str_contains($value, 'cosmetic') && str_contains($value, 'non') && str_contains($value, 'invasive')) {
            return 'cosmetic_aesthetic_non_invasive';
        } elseif (str_contains($value, 'cosmetic') && str_contains($value, 'surgical')) {
            return 'cosmetic_aesthetic_non_surgical_invasive';
        }
        
        return 'core_services'; // default
    }

    /**
     * Map CSV practice area to form values
     */
    private function mapPracticeArea($csvPracticeArea, $csvCoveredArea)
    {
        $area = strtolower(trim($csvPracticeArea));
        $covered = strtolower(trim($csvCoveredArea));
        
        // Combine both fields for better matching
        $combined = $area . ' ' . $covered;
        
        // Dental practice areas
        if (str_contains($combined, 'general dental') && str_contains($combined, 'specialised')) {
            return 'general_dental_practitioners_accredited_specialised_procedures';
        } elseif (str_contains($combined, 'general dental')) {
            return 'general_dental_practice';
        }
        
        // Medical practice areas
        if (str_contains($combined, 'core services with procedures')) {
            return 'core_services_with_procedures';
        } elseif (str_contains($combined, 'core services')) {
            return 'core_services';
        } elseif (str_contains($combined, 'obstetrics')) {
            return 'general_practitioner_with_obstetrics';
        } elseif (str_contains($combined, 'cosmetic') && str_contains($combined, 'non') && str_contains($combined, 'invasive')) {
            return 'cosmetic_aesthetic_non_invasive';
        } elseif (str_contains($combined, 'cosmetic') && str_contains($combined, 'surgical')) {
            return 'cosmetic_aesthetic_surgical_invasive';
        }
        
        // Specialist areas
        if (str_contains($combined, 'orthopaedics')) {
            return 'office_clinical_orthopaedics';
        } elseif (str_contains($combined, 'ophthalmology')) {
            return 'ophthalmology_surgeries_non_ga';
        }
        
        return 'general_practice'; // default
    }

    /**
     * Map CSV locum practice location to form values
     */
    private function mapLocumPracticeLocation($csvValue)
    {
        $value = strtolower(trim($csvValue));
        
        if (str_contains($value, 'private clinic')) {
            return 'private_clinic';
        } elseif (str_contains($value, 'private hospital') || str_contains($value, 'hospital')) {
            return 'private_hospital';
        }
        
        return ''; // empty if not locum
    }
}
