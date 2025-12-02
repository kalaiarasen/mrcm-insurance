<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GenerateClientCodesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('Generating client codes for existing users...');

        // Get all users with 'client' role who don't have a client_code
        $clients = User::role('client')
            ->whereNull('client_code')
            ->orderBy('id')
            ->get();

        if ($clients->isEmpty()) {
            $this->command->info('No clients found without client_code.');
            return;
        }

        $updated = 0;

        foreach ($clients as $client) {
            // Generate client code in format #C{5-digit-random-number}
            // Check if code already exists and generate new if needed
            do {
                $randomNumber = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
                $clientCode = '#C' . $randomNumber;
                $exists = User::where('client_code', $clientCode)->exists();
            } while ($exists);

            // Update client with new code
            $client->client_code = $clientCode;
            $client->save();

            $updated++;

            $this->command->info("Assigned {$clientCode} to {$client->name} (ID: {$client->id})");
        }

        $this->command->info("Successfully generated {$updated} client codes.");
    }
}
