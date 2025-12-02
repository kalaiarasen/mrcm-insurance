<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ApproveExistingAgentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Agent role
        $agentRole = Role::where('name', 'Agent')->first();

        if (!$agentRole) {
            $this->command->error('Agent role not found!');
            return;
        }

        // Get all users with Agent role
        $agents = User::role('Agent')->get();

        $count = 0;
        foreach ($agents as $agent) {
            $agent->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => 1, // Assuming admin user ID is 1
            ]);
            $count++;
            $this->command->info("Approved agent: {$agent->name} (ID: {$agent->id})");
        }

        $this->command->info("Successfully approved {$count} existing agents.");
    }
}
