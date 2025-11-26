<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ExcelMedicalSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/Excel_Medical.csv');

        if (!file_exists($path)) {
            $this->command->error("Excel_Medical.csv not found in storage/app/");
            return;
        }

        if (($handle = fopen($path, 'r')) === false) {
            $this->command->error("Cannot open Excel_Medical.csv");
            return;
        }

        $header = fgetcsv($handle); // skip header row

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {

                if (count($row) < 20) {
                    // Skip incomplete rows
                    continue;
                }

                // Read columns
                $name               = trim($row[1] ?? '');
                $nationality        = trim($row[2] ?? '');
                $nric               = trim($row[3] ?? '');
                $passport_no        = trim($row[5] ?? '');
                $gender             = trim($row[6] ?? '');
                $contact_no         = trim($row[7] ?? '');
                $email              = trim($row[8] ?? '');
                $mailing_address    = trim($row[9] ?? '');
                $primary_address    = trim($row[10] ?? '');
                $secondary_address  = trim($row[11] ?? '');

                // Qualifications
                $ins1 = trim($row[12] ?? '');
                $deg1 = trim($row[13] ?? '');
                $ins2 = trim($row[14] ?? '');
                $deg2 = trim($row[15] ?? '');
                $ins3 = trim($row[16] ?? '');
                $deg3 = trim($row[17] ?? '');

                // Insurance fields
                $policy_no      = trim($row[21] ?? '');
                $expiry_date    = trim($row[22] ?? '');
                $employment     = trim($row[23] ?? '');
                $specialty      = trim($row[24] ?? '');
                $premium        = trim($row[26] ?? '');
                $policy_limit   = trim($row[27] ?? '');

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
                        'nationality'          => $nationality,
                        'nric'                 => $nric,
                        'passport_no'          => $passport_no,
                        'employment_status'    => $employment,
                        'specialty'            => $specialty,
                        'policy_no'            => $policy_no,
                        'policy_expiry_date'   => $expiry_date,
                        'premium'              => $premium,
                        'policy_limit'         => $policy_limit,
                        'updated_at'           => now(),
                        'created_at'           => now(),
                    ]
                );

                // -------------------------
                // 3. CONTACTS
                // -------------------------
                DB::table('applicant_contacts')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'phone'             => $contact_no,
                        'mailing_address'   => $mailing_address,
                        'primary_address'   => $primary_address,
                        'secondary_address' => $secondary_address,
                        'updated_at'        => now(),
                        'created_at'        => now(),
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
                        'qualification' => $qual,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }

            DB::commit();
            fclose($handle);

            $this->command->info("Excel_Medical.csv has been successfully imported!");

        } catch (\Exception $e) {

            DB::rollBack();
            fclose($handle);

            $this->command->error("Seeder failed: " . $e->getMessage());
        }
    }
}
