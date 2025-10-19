<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For each table, add is_used column and index only if they don't already exist
        $tables = [
            'applicant_profiles' => 'registration_number',
            'qualifications' => 'year_obtained',
            'addresses' => 'clinic_name',
            'applicant_contacts' => 'email_address',
            'healthcare_services' => 'practice_area',
            'policy_pricings' => 'total_payable',
            'risk_managements' => 'sterilisation_facilities',
            'insurance_histories' => 'claims_details',
            'claims_experiences' => 'claim_amounts_paid',
            'policy_applications' => 'submitted_at',
        ];

        foreach ($tables as $tableName => $afterColumn) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'is_used')) {
                Schema::table($tableName, function (Blueprint $table) use ($afterColumn) {
                    $table->boolean('is_used')->default(true)->after($afterColumn)->comment('Is this the active/latest version');
                });
                
                // Create index separately using raw SQL to avoid issues
                try {
                    DB::statement("ALTER TABLE `{$tableName}` ADD INDEX `{$tableName}_user_id_is_used_index`(`user_id`, `is_used`)");
                } catch (\Exception $e) {
                    // Index might already exist
                }
            }
        }
    }

    public function down(): void
    {
        // Drop columns and their indices
        $tables = ['applicant_profiles', 'qualifications', 'addresses', 'applicant_contacts', 'healthcare_services', 'policy_pricings', 'risk_managements', 'insurance_histories', 'claims_experiences', 'policy_applications'];
        
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'is_used')) {
                DB::statement("ALTER TABLE `{$table}` DROP COLUMN `is_used`");
            }
        }
    }
};

