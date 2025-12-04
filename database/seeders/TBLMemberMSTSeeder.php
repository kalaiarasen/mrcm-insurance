<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TBLMemberMSTSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/TBL_Member_MST.csv');

        if (!file_exists($path)) {
            $this->command->error("TBL_Member_MST.csv not found in storage/app/");
            return;
        }

        if (($handle = fopen($path, 'r')) === false) {
            $this->command->error("Cannot open TBL_Member_MST.csv");
            return;
        }

        $header = fgetcsv($handle); // skip header row

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {

                // -------------------------
                // 1. USERS
                // -------------------------
                $memberId = trim($row[0] ?? '');
                $name     = trim($row[2] ?? '');
                $title    = trim($row[1] ?? '');
                $email    = trim($row[9] ?? '');
                $password = trim($row[10] ?? '');
                $contact = trim($row[13] ?? '');
                $gender   = trim($row[7] ?? '');
                $status   = trim($row[70] ?? 1);
                $signature   = trim($row[69] ?? NULL);

                if (!$memberId || (!$name && !$email)) continue;

                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'               => $name,
                        'title'              => $title ?: null,
                        'gender'             => $gender ?: null,
                        'contact_no'         => $contact ?: null,
                        'password'           => $password ? Hash::make($password) : Hash::make('password123'),
                        'application_status' => $status,
                        'old_member_id'      => $memberId,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                        'signature' => (!is_null($signature) && $signature !== '' && strtoupper($signature) !== 'NULL') ? "app/{$signature}" : null,
                        'nationality_status' => trim($row[4] ?? NULL),
                        'registration_council' => strtolower(trim($row[43] ?? NULL)),
                        'registration_number' => trim($row[44] ?? NULL),
                    ]
                );

                $userId = $user->id;

                $mailingAddress     = trim($row[14] ?? '');
                $mailingPostcode = trim($row[15] ?? '');
                $mailingState    = trim($row[16] ?? '');
                $mailingCity     = trim($row[17] ?? '');
                $mailingCountry  = trim($row[18] ?? '');

                if ($mailingAddress) {
                    DB::table('addresses')->updateOrInsert(
                        ['user_id' => $userId, 'type' => 'mailing'],
                        [
                            'address'    => $mailingAddress,
                            'postcode'   => $mailingPostcode,
                            'state'      => $mailingState,
                            'city'       => $mailingCity,
                            'country'    => $mailingCountry,
                            'is_used'    => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // -------------------------
                // 2. PRIMARY ADDRESS
                // -------------------------
                $primaryType     = trim($row[19] ?? '');
                $primaryClinic   = trim($row[20] ?? '');
                $primaryAddress  = trim($row[21] ?? '');
                $primaryPostcode = trim($row[22] ?? '');
                $primaryState    = trim($row[23] ?? '');
                $primaryCity     = trim($row[24] ?? '');
                $primaryCountry  = trim($row[25] ?? '');

                if ($primaryAddress) {
                    DB::table('addresses')->updateOrInsert(
                        ['user_id' => $userId, 'type' => 'primary_clinic', 'clinic_name' => $primaryClinic],
                        [
                            'clinic_type' => $primaryType,
                            'address'    => $primaryAddress,
                            'postcode'   => $primaryPostcode,
                            'state'      => $primaryState,
                            'city'       => $primaryCity,
                            'country'    => $primaryCountry,
                            'is_used'    => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // -------------------------
                // 3. SECONDARY ADDRESS
                // -------------------------
                $secondaryType     = trim($row[26] ?? '');
                $secondaryClinic   = trim($row[27] ?? '');
                $secondaryAddress  = trim($row[28] ?? '');
                $secondaryPostcode = trim($row[29] ?? '');
                $secondaryState    = trim($row[30] ?? '');
                $secondaryCity     = trim($row[31] ?? '');
                $secondaryCountry  = trim($row[32] ?? '');

                if ($secondaryAddress) {
                    DB::table('addresses')->updateOrInsert(
                        ['user_id' => $userId, 'type' => 'secondary_clinic', 'clinic_name' => $secondaryClinic],
                        [
                            'address'    => $secondaryAddress,
                            'postcode'   => $secondaryPostcode,
                            'state'      => $secondaryState,
                            'city'       => $secondaryCity,
                            'country'    => $secondaryCountry,
                            'is_used'    => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // -------------------------
                // 4. QUALIFICATIONS
                // -------------------------
                $qualifications = [
                    [trim($row[34] ?? ''), trim($row[35] ?? ''), trim($row[36] ?? '')],
                    [trim($row[37] ?? ''), trim($row[38] ?? ''), trim($row[39] ?? '')],
                    [trim($row[40] ?? ''), trim($row[41] ?? ''), trim($row[42] ?? '')],
                ];

                DB::table('qualifications')->where('user_id', $userId)->delete();

                foreach ($qualifications as $index => [$institution, $degree, $year]) {
                    // Skip if both institution and degree are empty or NULL string
                    if (empty($institution) && empty($degree)) continue;
                    if ($institution === 'NULL') $institution = null;
                    if ($degree === 'NULL') $degree = null;
                    
                    // Clean year value - store as string to handle legacy data
                    if ($year === 'NULL' || empty($year)) {
                        $year = null;
                    } else {
                        $year = trim($year);
                    }
                    
                    DB::table('qualifications')->insert([
                        'user_id'                 => $userId,
                        'sequence'                => $index + 1, // 1, 2, 3
                        'institution'             => $institution,
                        'degree_or_qualification' => $degree,
                        'year_obtained'           => $year,
                        'created_at'              => now(),
                        'updated_at'              => now(),
                    ]);
                }

                // -------------------------
                // 5. RISK MANAGEMENT
                // -------------------------
                DB::table('risk_managements')->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'medical_records'        => $this->yesNoToBool($row[47] ?? ''),
                        'informed_consent'       => $this->yesNoToBool($row[48] ?? ''),
                        'adverse_incidents'      => $this->yesNoToBool($row[49] ?? ''),
                        'sterilisation_facilities'=> $this->yesNoToBool($row[50] ?? ''),
                        'is_used'                => 1,
                        'created_at'             => now(),
                        'updated_at'             => now(),
                    ]
                );

                // -------------------------
                // 6. INSURANCE HISTORIES
                // -------------------------

                $policyLimit = trim($row[48] ?? '');
                if (strtolower($policyLimit) === 'unlimited coverage') {
                    $policyLimit = 999999999999.99; // max for decimal(15,2)
                } elseif (!is_numeric($policyLimit)) {
                    $policyLimit = null;
                } else {
                    $policyLimit = (float) $policyLimit;
                }
                $excess = trim($row[49] ?? '');
                if (strtolower($excess) === 'yes' || strtolower($excess) === '-' || strtolower($excess) === 'unlimited') {
                    $excess = 0; // or null if you prefer
                } elseif (!is_numeric($excess)) {
                    $excess = null;
                } else {
                    $excess = (float) $excess;
                }


                DB::table('insurance_histories')->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'current_insurance'   => $this->yesNoToBool($row[45] ?? ''),
                        'period_of_insurance' => $row[46] ?? null,
                        'insurer_name'        => $row[47] ?? null,
                        'policy_limit_myr'    => $policyLimit,
                        'excess_myr'          => $excess,
                        'retroactive_date'    => $row[50] ?? null,
                        'is_used'             => 1,
                        'created_at'          => now(),
                        'updated_at'          => now(),
                    ]
                );

                // -------------------------
                // 7. CLAIMS EXPERIENCES
                // -------------------------
                DB::table('claims_experiences')->updateOrInsert(
                    ['user_id' => $userId],
                    [
                        'claims_made'        => $this->yesNoToBool($row[59] ?? ''),
                        'aware_of_errors'    => $this->yesNoToBool($row[60] ?? ''),
                        'disciplinary_action'=> $this->yesNoToBool($row[61] ?? ''),
                        'is_used'            => 1,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]
                );

                // -------------------------
                // 8. CLAIM DOCUMENTS
                // -------------------------
                if ((!empty($row[69] ?? '') && $row[69] !== 'NUL')) {
                    $claimId = DB::table('claims')->insertGetId([
                        'user_id' => $userId,
                        'action' => 'new',
                        'incident_date' => now(),
                        'notification_date' => now(),
                        'claim_title' => 'Claim from Old System',
                        'claim_description' => 'This claim was imported from the legacy system.',
                        'status' => 'closed',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    DB::table('claim_documents')->updateOrInsert(
                        ['claim_id' => $claimId, 'document_path' => $row[69]],
                        [
                            'document_name' => basename($row[69]),
                            'mime_type' => 'application/octet-stream',
                            'file_size' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            DB::commit();
            fclose($handle);
            $this->command->info("TBL_Member_MST.csv has been successfully imported!");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->command->error("Seeder failed: " . $e->getMessage());
        }
    }

    private function yesNoToBool($value)
    {
        $v = strtolower(trim($value));
        return ($v === 'yes' || $v === '1') ? 1 : 0;
    }
}
