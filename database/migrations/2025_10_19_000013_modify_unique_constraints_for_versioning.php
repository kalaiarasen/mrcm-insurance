<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Remove global unique constraints to support versioning where multiple records 
     * can exist per user with the same NRIC/Passport/Registration number.
     */
    public function up(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            // Drop the old global unique constraints that prevent multiple versions
            $table->dropUnique('applicant_profiles_nric_number_unique');
            $table->dropUnique('applicant_profiles_passport_number_unique');
            $table->dropUnique('applicant_profiles_registration_number_unique');
        });

        Schema::table('applicant_contacts', function (Blueprint $table) {
            // Drop the email_address unique constraint to allow multiple versions
            $table->dropUnique('applicant_contacts_email_address_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use raw SQL to avoid issues with constraint names
        try {
            DB::statement('ALTER TABLE `applicant_profiles` ADD UNIQUE `applicant_profiles_nric_number_unique`(`nric_number`)');
        } catch (\Exception $e) {
            // Constraint might already exist
        }
        
        try {
            DB::statement('ALTER TABLE `applicant_profiles` ADD UNIQUE `applicant_profiles_passport_number_unique`(`passport_number`)');
        } catch (\Exception $e) {
            // Constraint might already exist
        }
        
        try {
            DB::statement('ALTER TABLE `applicant_profiles` ADD UNIQUE `applicant_profiles_registration_number_unique`(`registration_number`)');
        } catch (\Exception $e) {
            // Constraint might already exist
        }
        
        try {
            DB::statement('ALTER TABLE `applicant_contacts` ADD UNIQUE `applicant_contacts_email_address_unique`(`email_address`)');
        } catch (\Exception $e) {
            // Constraint might already exist
        }
    }
};

