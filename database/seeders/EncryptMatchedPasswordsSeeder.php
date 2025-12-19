<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class EncryptMatchedPasswordsSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/import/TBL_Member_MST.csv');

        if (!file_exists($path)) {
            $this->command->error('CSV file not found: storage/app/import/TBL_Member_MST.csv');
            return;
        }

        if (($handle = fopen($path, 'r')) === false) {
            $this->command->error('Unable to open CSV file');
            return;
        }

        fgetcsv($handle);

        DB::beginTransaction();

        try {
            $updated = 0;
            $skipped = 0;

            while (($row = fgetcsv($handle)) !== false) {

                $memberId = trim($row[0] ?? '');
                $email    = trim($row[9] ?? '');
                $password = trim($row[10] ?? '');

                if (!$password) {
                    $skipped++;
                    continue;
                }

                $user = User::where('old_member_id', $memberId)
                    ->orWhere('email', $email)
                    ->first();

                if (!$user || !$user->password) {
                    $skipped++;
                    continue;
                }

                if (!Hash::check($password, $user->password)) {
                    $skipped++;
                    continue;
                }

                if (empty($user->password_enc)) {
                    $user->password_enc = $password;
                    $user->save();
                    $updated++;
                }
            }

            DB::commit();
            fclose($handle);

            $this->command->info("EncryptMatchedPasswordsSeeder completed.");
            $this->command->info("Updated: {$updated}, Skipped: {$skipped}");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->command->error('Seeder failed: ' . $e->getMessage());
        }
    }
}
