<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsersOldDataSeeder extends Seeder
{
    // Store old_user_id → new user.id mapping
//    public static array $userMap = [];

    public function run(): void
    {
        $path = storage_path('app/import/TBL_Users_MST.csv');

        if (!file_exists($path)) {
            dd("CSV file not found: $path");
        }

        $file = fopen($path, 'r');

        // Skip header
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            [
                $numUserID,
                $numMemberID,
                $chvUserName,
                $chvPassword,
                $chvUserAuthToken,   // ignored
                $tnyUserType,
                $tnyStatus
            ] = $row;

            $roleName = match ((int)$tnyUserType) {
                1 => 'Admin',
                2 => 'Client',
                default => 'Client',
            };

            if ((int)$tnyUserType === 1) {
                continue;
            }
            // Prevent duplicate emails
            $email = $chvUserName; // or generate placeholder if empty
            if (User::where('email', $email)->exists()) {
                continue; // skip duplicates
            }

            // Create user and save old_user_id / old_member_id
            $user = User::create([
                'name' => $chvUserName,
                'email' => $email,
                'password' => Hash::make($chvPassword),
                'password_enc' => $chvPassword,
                'status' => (int)$tnyStatus,
                'old_user_id' => $numUserID,
                'old_member_id' => $numMemberID,
            ]);

            // Assign role using Spatie's method
            $user->assignRole($roleName);

//             Save mapping for other seeders
//            self::$userMap[$numUserID] = $user->id;
        }

        fclose($file);
        $this->command->info("Users imported successfully.");
    }
}
