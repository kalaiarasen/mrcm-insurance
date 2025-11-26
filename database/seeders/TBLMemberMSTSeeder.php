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

                $memberId = trim($row[0] ?? '');
                $name     = trim($row[2] ?? '');
                $email    = trim($row[6] ?? '');

                if (!$memberId || (!$name && !$email)) continue;

                $password = trim($row[7] ?? '');
                $gender   = trim($row[5] ?? '');
                $status   = trim($row[92] ?? 1);

                // -------------------------
                // 1. USERS - updateOrInsert by email
                // -------------------------
                $userId = null;
                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'       => $name,
                        'gender'     => $gender ?: null,
                        'password'   => $password ? Hash::make($password) : Hash::make('password123'),
                        'application_status' => $status,
                        'old_member_id'      => $memberId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $userId = $user->id;

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
                        ['user_id' => $userId, 'type' => $primaryType, 'clinic_name' => $primaryClinic],
                        [
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
                // 3. ADDRESSES (Secondary)
                // -------------------------
                $secondaryType     = trim($row[23] ?? '');
                $secondaryClinic   = trim($row[24] ?? '');
                $secondaryAddress  = trim($row[25]
