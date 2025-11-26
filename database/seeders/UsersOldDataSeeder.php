<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use DB;

class UsersOldDataSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/TBL_Users_MST.csv');

        if (!file_exists($path)) {
            dd("CSV file not found: $path");
        }

        $file = fopen($path, 'r');

        // Skip header row
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

            // Map tnyUserType to role_id
            $roleId = match ((int)$tnyUserType) {
                1 => 2,  // admin → role ID 2
                2 => 3,  // client → role ID 3
                default => 3,
            };

            // Preve
