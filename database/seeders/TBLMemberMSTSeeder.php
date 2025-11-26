<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TBLMemberMSTSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/TBL_Member_MST.csv');

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

                // Mandatory fields
                $memberId = trim($row[0] ?? '');
                $name     = trim($row[2] ?? '');
                $email    = trim($row[6] ?? '');

                if (!$memberId || (!$name && !$email)) {
                    continue; // skip invalid rows
                }

                $password = trim($row[7] ?? '');
                $gender   = trim($row[5] ?? '');
                $status   = trim($row[92] ?? 1);

                // -------------------------
                // 1. USERS
                // -------------------------
                $user = DB::table('users')->updateOrInsert(
                    ['id' => $memberId],
                    [
                        'name'      => $name,
                        'email'     => $email,
                        'gender'    => $gender ?: null,
                        'password'  => $password ? Hash::make($password) : Hash::make('password123'),
                        'application_status' => $status,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // -------------------------
                // 2. ADDRESSES (Primary)
                // -------------------------
                $primaryType     = trim($row[16] ?? '');
                $primaryClinic   = trim($row[17] ?? '');
                $primaryAddress  = trim($row[18] ?? '');
                $primaryPostcode = trim($row[19] ?? '');
                $primaryState    = trim($row[20] ?? '');
                $primaryCity     = trim($row[21] ?? '');
                $primaryCountry  = trim($row[22] ?? '');

                if ($primaryAddress) {
                    DB::table('addresses')->updateOrInsert(
                        ['user_id' => $memberId, 'type' => $primaryType, 'clinic_name' => $primaryClinic],
                        [
                            'address'     => $primaryAddress,
                            'postcode'    => $primaryPostcode,
                            'state'       => $primaryState,
                            'city'        => $primaryCity,
                            'country'     => $primaryCountry,
                            'is_used'     => 1,
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ]
                    );
                }

                // -------------------------
                // 3. ADDRESSES (Secondary)
                // -------------------------
                $secondaryType     = trim($row[23] ?? '');
                $secondaryClinic   = trim($row[24] ?? '');
                $secondaryAddress  = trim($row[25] ?? '');
                $secondaryPostcode = trim($row[26] ?? '');
                $secondaryState    = trim($row[27] ?? '');
                $secondaryCity     = trim($row[28] ?? '');
                $secondaryCountry  = trim($row[29] ?? '');

                if ($secondaryAddress) {
                    DB::table('addresses')->updateOrInsert(
                        ['user_id' => $memberId, 'type' => $secondaryType, 'clinic_name' => $secondaryClinic],
                        [
                            'address'     => $secondaryAddress,
                            'postcode'    => $secondaryPostcode,
                            'state'       => $secondaryState,
                            'city'        => $secondaryCity,
                            'country'     => $secondaryCountry,
                            'is_used'     => 1,
                            'created_at'  => now(),
                            'updated_at'  => now(),
                        ]
                    );
                }

                // -------------------------
                // 4. QUALIFICATIONS
                // -------------------------
                $qualifications = [
                    [trim($row[30] ?? ''), trim($row[31] ?? ''), trim($row[32] ?? '')],
                    [trim($row[33] ?? ''), trim($row[34] ?? ''), trim($row[35] ?? '')],
                    [trim($row[36] ?? ''), trim($row[37] ?? ''), trim($row[38] ?? '')],
                ];

                DB::table('qualifications')->where('user_id', $memberId)->delete();

                foreach ($qualifications as [$institution, $degree, $year]) {
                    if (!$institution && !$degree) continue;
                    DB::table('qualifications')->insert([
                        'user_id'       => $memberId,
                        'institution'   => $institution,
                        'degree_or_qualification' => $degree,
                        'year_obtained' => $year ?: null,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }

                // -------------------------
                // 5. RISK MANAGEMENT
                // -------------------------
                DB::table('risk_managements')->updateOrInsert(
                    ['user_id' => $memberId],
                    [
                        'medical_records'  => $this->yesNoToBool($row[41] ?? ''),
                        'informed_consent' => $this->yesNoToBool($row[42] ?? ''),
                        'adverse_incidents'=> $this->yesNoToBool($row[43] ?? ''),
                        'sterilisation_facilities' => $this->yesNoToBool($row[44] ?? ''),
                        'is_used'          => 1,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]
                );

                // -------------------------
                // 6. INSURANCE HISTORIES
                // -------------------------
                DB::table('insurance_histories')->updateOrInsert(
                    ['user_id' => $memberId],
                    [
                        'current_insurance'   => $this->yesNoToBool($row[45] ?? ''),
                        'period_of_insurance' => $row[46] ?? null,
                        'insurer_name'        => $row[47] ?? null,
                        'policy_limit_myr'    => $row[48] ?? null,
                        'excess_myr'          => $row[49] ?? null,
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
                    ['user_id' => $memberId],
                    [
                        'claims_made'       => $this->yesNoToBool($row[53] ?? ''),
                        'aware_of_errors'   => $this->yesNoToBool($row[54] ?? ''),
                        'disciplinary_action'=> $this->yesNoToBool($row[55] ?? ''),
                        'is_used'           => 1,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]
                );

                // -------------------------
                // 8. CLAIM DOCUMENTS
                // -------------------------
                if (!empty($row[62] ?? '')) {
                    DB::table('claim_documents')->updateOrInsert(
                        ['user_id' => $memberId, 'document_path' => $row[62]],
                        [
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
