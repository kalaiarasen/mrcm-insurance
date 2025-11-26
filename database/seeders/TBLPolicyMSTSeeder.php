<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            while (($row = fgetcsv($handle)) !== false) {

                $memberId = trim($row[4] ?? '');

                if (!$memberId) {
                    $this->command->warn("numMemberID missing. Skipping row.");
                    continue;
                }

                // -------------------------
                // 1. FIND USER
                // -------------------------
                $user = DB::table('users')->where('id', $memberId)->first();

                if (!$user) {
                    $this->command->warn("User with numMemberID {$memberId} not found. Skipping row.");
                    continue;
                }

                // -------------------------
                // 2. INSERT/UPDATE healthcare_services
                // -------------------------
                DB::table('healthcare_services')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'professional_indemnity_type' => trim($row[5] ?? ''),
                        'cover_type'                  => trim($row[6] ?? $row[8] ?? ''),
                        'specialty_area'              => trim($row[7] ?? $row[8] ?? ''),
                        'employment_status'           => trim($row[9] ?? ''),
                        'service_type'                => trim($row[8] ?? ''),
                        'practice_area'               => trim($row[10] ?? ''),
                        'locum_practice_location'     => trim($row[11] ?? ''),
                        'is_used'                     => 1,
                        'created_at'                  => now(),
                        'updated_at'                  => now(),
                    ]
                );

                // -------------------------
                // 3. INSERT/UPDATE policy_pricings
                // -------------------------
                DB::table('policy_pricings')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'liability_limit'   => $this->toNumeric($row[12] ?? 0),
                        'gross_premium'     => $this->toNumeric($row[13] ?? 0),
                        'locum_extension'   => $this->toNumeric($row[15] ?? 0),
                        'policy_start_date' => $this->toDate($row[16] ?? null),
                        'policy_expiry_date'=> $this->toDate($row[17] ?? null),
                        'locum_addon'       => $this->yesNoToBool($row[14] ?? 0),
                        'sst'               => $this->toNumeric($row[18] ?? 0),
                        'total_payable'     => $this->toNumeric($row[21] ?? 0),
                        'payment_document'  => trim($row[27] ?? ''),
                        'policy_schedule_path'=> trim($row[26] ?? ''),
                        'is_used'           => $this->yesNoToBool($row[22] ?? 1),
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]
                );
            }

            DB::commit();
            fclose($handle);
            $this->command->info("TBL_Policy_MST.csv has been successfully imported!");

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

    private function toNumeric($value)
    {
        $value = str_replace([',', '$', ' '], '', $value);
        return is_numeric($value) ? (float)$value : 0;
    }

    private function toDate($value)
    {
        if (!$value) return null;
        try {
            return date('Y-m-d', strtotime($value));
        } catch (\Exception $e) {
            return null;
        }
    }
}
