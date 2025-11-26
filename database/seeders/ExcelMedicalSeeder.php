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
                        'name'       => $name ?: 'Unknown',
                        'email'      => $email ?: null,
                        'gender'     => $gender ?: null,
                        'contact_no' => $contact_no ?: null,
                        'password'   => Hash::make('password123'),
                    ]);
                } else {
                    // Update contact_no if user exists
                    if ($contact_no) {
                        $user->update(['contact_no' => $contact_no]);
                    }
                }

                // -------------------------
                // 2. PROFILE
                // -------------------------
                DB::table('applicant_profiles')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'nric_number'      => $nric ?: null,
                        'passport_number'  => $passport_no ?: null,
                        'updated_at'       => now(),
                        'created_at'       => now(),
                    ]
                );

                // -------------------------
                // 3. CONTACTS
                // -------------------------
                DB::table('applicant_contacts')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'contact_no'     => $contact_no ?: null,
                        'email_address'  => $email ?: null,
                        'updated_at'     => now(),
                        'created_at'     => now(),
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

                $sequence = 1;
                foreach ($qualifications as [$inst, $qual]) {
                    if ($inst === '' && $qual === '') continue;

                    DB::table('qualifications')->insert([
                        'user_id'                => $user->id,
                        'sequence'               => $sequence,
                        'institution'            => $inst ?: null,
                        'degree_or_qualification'=> $qual ?: null,
                        'year_obtained'          => null,
                        'created_at'             => now(),
                        'updated_at'             => now(),
                    ]);
                    
                    $sequence++;
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
