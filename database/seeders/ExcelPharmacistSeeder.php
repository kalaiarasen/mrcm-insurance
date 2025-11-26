<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ExcelPharmacistSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/Excel_Pharmacist.csv');

        if (!file_exists($path)) {
            $this->command->error("Excel_Pharmacist.csv not found in storage/app/");
            return;
        }

        if (($handle = fopen($path, 'r')) === false) {
            $this->command->error("Cannot open Excel_Pharmacist.csv");
            return;
        }

        $header = fgetcsv($handle); // skip header row

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {

                // Read columns
                $name               = trim($row[0] ?? '');
                $nationality        = trim($row[1] ?? '');
                $nric               = trim($row[2] ?? '');
                $passport_no        = trim($row[3] ?? '');
                $gender             = trim($row[4] ?? '');
                $contact_no         = trim($row[5] ?? '');
                $email              = trim($row[6] ?? '');
                $mailing_address    = trim($row[7] ?? '');
                $primary_address    = trim($row[8] ?? '');
                $secondary_address  = trim($row[9] ?? '');

                // Qualifications
                $ins1 = trim($row[10] ?? '');
                $deg1 = trim($row[11] ?? '');
                $ins2 = trim($row[12] ?? '');
                $deg2 = trim($row[13] ?? '');
                $ins3 = trim($row[14] ?? '');
                $deg3 = trim($row[15] ?? '');

                // Policy fields
                $policy_no      = trim($row[18] ?? '');
                $expiry_date    = trim($row[19] ?? '');
                $plan           = trim($row[20] ?? '');
                $policy_limit   = trim($row[21] ?? '');

                if ($name === '' && $email === '') {
                    continue; // skip empty rows
                }

                // -------------------------
                // 1. MATCH USER
                // -------------------------
                $user = null;

                if ($email !== '') {
                    $user = User::where('email', $email)->first();
                }

                if (!$user && $name !== '') {
                    $user = User::where('name', $name)->first();
                }

                // If still not found → create new user
                if (!$user) {
                    $user = User::create([
                        'name'      => $name ?: 'Unknown',
                        'email'     => $email ?: null,
                        'gender'    => $gender ?: null,
                        'password'  => Hash::make('password123'),
                    ]);
                }

                // -------------------------
                // 2. PROFILE
                // -------------------------
                DB::table('applicant_profiles')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'nationality_status' => $nationality,
                        'nric_number'        => $nric,
                        'passport_number'    => $passport_no,
                        'policy_no'          => $policy_no,
                        'policy_expiry_date' => $expiry_date,
                        'premium'            => $plan,
                        'policy_limit'       => $policy_limit,
                        'updated_at'         => now(),
                        'created_at'         => now(),
                    ]
                );

                // -------------------------
                // 3. CONTACTS
                // -------------------------
                DB::table('applicant_contacts')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'contact_no'       => $contact_no,
                        'email_address'    => $email,
                        'mailing_address'  => $mailing_address,
                        'primary_address'  => $primary_address,
                        'secondary_address'=> $secondary_address,
                        'updated_at'       => now(),
                        'created_at'       => now(),
                    ]
                );

                // -------------------------
                // 4. QUALIFICATIONS (max 3)
                // -------------------------
                $qualifications = [
                    [$ins1, $deg1],
                    [$ins2, $deg2],
                    [$ins3, $deg3],
                ];

                DB::table('qualifications')->where('user_id', $user->id)->delete();

                foreach ($qualifications as [$inst, $qual]) {
                    if ($inst === '' && $qual === '') continue;

                    DB::table('qualifications')->insert([
                        'user_id'       => $user->id,
                        'institution'   => $inst,
                        'degree_or_qualification' => $qual,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }

                // -------------------------
                // 5. POLICY PRICINGS
                // -------------------------
                DB::table('policy_pricings')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'policy_expiry_date' => $expiry_date,
                        'gross_premium'      => $plan,
                        'liability_limit'    => $policy_limit,
                        'is_used'            => 1,
                        'updated_at'         => now(),
                        'created_at'         => now(),
                    ]
                );
            }

            DB::commit();
            fclose($handle);
            $this->command->info("Excel_Pharmacist.csv has been successfully imported!");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->command->error("Seeder failed: " . $e->getMessage());
        }
    }
}
