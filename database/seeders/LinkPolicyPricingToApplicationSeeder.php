<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PolicyPricing;
use App\Models\PolicyApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LinkPolicyPricingToApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder links PolicyPricing records to PolicyApplication records
     * by matching user_id and is_used=true flag.
     */
    public function run(): void
    {
        $this->command->info('Starting to link PolicyPricing to PolicyApplication...');
        
        // Get all policy applications
        $policyApplications = PolicyApplication::with('user')->orderBy('created_at', 'desc')->get();
        
        $linked = 0;
        $notFound = 0;
        $multipleFound = 0;
        
        foreach ($policyApplications as $application) {
            // Find ALL pricing records for this application's user created within 1 minute
            $pricings = PolicyPricing::where('user_id', $application->user_id)
                ->whereBetween('created_at', [
                    $application->created_at->subMinute(),
                    $application->created_at->addMinute()
                ])
                ->get();
            
            if ($pricings->count() > 0) {
                foreach ($pricings as $pricing) {
                    $pricing->policy_application_id = $application->id;
                    $pricing->save();
                    $linked++;
                }
                
                if ($pricings->count() > 1) {
                    $multipleFound++;
                    $this->command->info("Linked {$pricings->count()} pricings to application ID {$application->id}");
                } else {
                    $this->command->info("Linked pricing ID {$pricings->first()->id} to application ID {$application->id}");
                }
            } else {
                // Fallback: If no pricing found within 1 minute, try to find the latest one created BEFORE the application
                // This handles cases where pricing was generated significantly earlier than submission
                $pricing = PolicyPricing::where('user_id', $application->user_id)
                    ->where('created_at', '<=', $application->created_at)
                    ->whereNull('policy_application_id') // Only pick unlinked ones
                    ->orderBy('created_at', 'desc')
                    ->first();

                if ($pricing) {
                    $pricing->policy_application_id = $application->id;
                    $pricing->save();
                    $linked++;
                    $this->command->info("Linked pricing ID {$pricing->id} to application ID {$application->id} (fallback: latest before app)");
                } else {
                    $notFound++;
                    $this->command->warn("No pricing found for application ID {$application->id} (user_id: {$application->user_id})");
                }
            }
        }
        
        $this->command->info("\n=== Summary ===");
        $this->command->info("Total applications processed: {$policyApplications->count()}");
        $this->command->info("Successfully linked: {$linked}");
        $this->command->info("No pricing found: {$notFound}");
        $this->command->info("Multiple pricings found: {$multipleFound}");
        $this->command->info("\nDone!");
    }
}
