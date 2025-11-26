<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ExcelDentalSeeder extends Seeder
{
    public function run()
    {
        $file = storage_path('app/Excel_Dental.csv');

        if (!file_exists($file)) {
            dd("CSV file not found: " . $file);
        }

        $handle = fopen($file, 'r');

        // Read header
        $header = fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {

            $data = array_combine($header, $row);

            // ============================
            // 1. USERS TABLE
            // ============================
            $email = trim($data['Email Address']);
            $name = trim($data['Name']);
            $contact = trim($data['Contact No']);

            if (!$email && !$name) {
                continue; // skip invalid row
            }

            // Match existing user
            $user = User::where('email', $email)->first();

            if (!$user && $email) {
                $user = User::where('name', $name)->first();
            }

            // If still not found → create new
            if (!$user) {
                $user = User::create([
                    'name' => $name ?: 'Unknown',
                    'email' => $email ?: strtolower(str_replace(' ', '_', $name)) . '@imported.local',
                    'contact_no' => $contact,
                    'password' => Hash::make('Default123!'), // default
                ]);
            } else {
                $user->update([
                    'contact_no' => $contact ?: $user->contact_no,
                ]);
            }

            // ============================
            // 2. APPLICANT PROFILES
            // ============================
            DB::table('applicant_profiles')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'nationality_status' => $data['Nationality Status'] ?? null,
                    'nric_number' => $data['NRIC Number'] ?? null,
                    'passport_number' => $data['Passport Number'] ?? null,
                    'gender' => $data['Gender'] ?? null,
                    'is_used' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            // ============================
            // 3. APPLICANT CONTACTS
            // ============================
            DB::table('applicant_contacts')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'contact_no' => $contact,
                ],
                [
                    'email_address' => $email,
                    'is_used' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            // ============================
            // 4. ADDRESSES
            // ============================
            $addresses = [
                'mailing' => $data['Mailing Address'] ?? null,
                'primary' => $data['PrimaryAddr'] ?? null,
                'secondary' => $data['SecondaryAddr'] ?? null,
            ];

            foreach ($addresses as $type => $address) {
                if ($address) {
                    DB::table('addresses')->updateOrInsert(
                        ['user_id' => $user->id, 'type' => $type],
                        [
                            'address' => $address,
                            'is_used' => 1,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            }

            // ============================
            // 5. QUALIFICATIONS
            // ============================
            $degreeMapping = [
                1 => ['inst' => 'Institution (1# Degree)', 'deg' => '1# Degree or Qualification'],
                2 => ['inst' => 'Institution (2# Degree)', 'deg' => '2# Degree or Qualification'],
                3 => ['inst' => 'Institution (3# Degree)', 'deg' => '3# Degree or Qualification'],
            ];

            foreach ($degreeMapping as $seq => $col) {
                if (!empty($data[$col['inst']]) || !empty($data[$col['deg']])) {
                    DB::table('qualifications')->updateOrInsert(
                        [
                            'user_id' => $user->id,
                            'sequence' => $seq,
                        ],
                        [
                            'institution' => $data[$col['inst']] ?? null,
                            'degree_or_qualification' => $data[$col['deg']] ?? null,
                            'is_used' => 1,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }
            }

            // ============================
            // 6. HEALTHCARE SERVICES
            // ============================
            DB::table('healthcare_services')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'employment_status' => $data['Employment status'] ?? null,
                    'specialty_area' => $data['Specialty'] ?? null,
                    'is_used' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            // ============================
            // 7. POLICY PRICINGS
            // ============================
            DB::table('policy_pricings')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'policy_expiry_date' => $data['Expiry date'] ?? null,
                    'gross_premium' => $data['Premium'] ?? null,
                    'liability_limit' => $data['Policy limit'] ?? null,
                    'is_used' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        fclose($handle);

        echo "Excel Dental Seeder Completed.\n";
    }
}
