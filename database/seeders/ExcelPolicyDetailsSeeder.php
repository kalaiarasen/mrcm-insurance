<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExcelPolicyDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/Excel_PolicyDetails.csv');

        if (!file_exists($path)) {
            $this->command->error("Excel_PolicyDetails.csv not found in storage/app/");
            return;
        }

        if (($handle = fopen($path, 'r')) === false) {
            $this->command->error("Cannot open Excel_PolicyDetails.csv");
            return;
        }

        $header = fgetcsv($handle); // skip header row

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {

                // Read columns
                $memuid                  = trim($row[11] ?? '');
                $professional_indemnity   = trim($row[0] ?? '');
                $cover_type               = trim($row[1] ?? '');
                $medical_status           = trim($row[2] ?? '');
                $service_type             = trim($row[3] ?? '');
                $employment_status        = trim($row[4] ?? '');
                $type_of_cover            = trim($row[5] ?? '');
                $practice_area            = trim($row[6] ?? '');
                $covered_area             = trim($row[7] ?? '');
                $liability                = trim($row[8] ?? '');
                $premium                  = trim($row[9] ?? '');
                $locum_extension          = trim($row[10] ?? '');

                if (!$memuid) {
                    continue; // skip rows with no user mapping
                }

                // -------------------------
                // 1. FIND USER
                // -------------------------
                $user = DB::table('users')->where('id', $memuid)->first();

                if (!$user) {
                    $this->command->warn("User MEMUID {$memuid} not found. Skipping row.");
                    continue;
                }

                // -------------------------
                // 2. INSERT/UPDATE healthcare_services
                // -------------------------
                DB::table('healthcare_services')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'professional_indemnity_type' => $professional_indemnity,
                        'cover_type'                  => $cover_type ?: $type_of_cover,
                        'employment_status'           => $employment_status,
                        'specialty_area'              => $medical_status,
                        'service_type'                => $service_type,
                        'practice_area'               => $practice_area,
                        'locum_practice_location'     => $covered_area,
                        'is_used'                     => 1,
                        'updated_at'                  => now(),
                        'created_at'                  => now(),
                    ]
                );

                // -------------------------
                // 3. INSERT/UPDATE policy_pricings
                // -------------------------
                DB::table('policy_pricings')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'liability_limit' => $liability ?: null,
                        'gross_premium'   => $premium ?: null,
                        'locum_extension' => $locum_extension ?: null,
                        'is_used'         => 1,
                        'updated_at'      => now(),
                        'created_at'      => now(),
                    ]
                );
            }

            DB::commit();
            fclose($handle);
            $this->command->info("Excel_PolicyDetails.csv has been successfully imported!");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->command->error("Seeder failed: " . $e->getMessage());
        }
    }
}
