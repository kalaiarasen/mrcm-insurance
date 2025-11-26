<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            // Drop the unique constraint on reference_number
            $table->dropUnique('policy_applications_reference_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            // Re-add the unique constraint
            $table->unique('reference_number', 'policy_applications_reference_number_unique');
        });
    }
};
