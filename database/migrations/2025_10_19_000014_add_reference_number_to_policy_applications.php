<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add reference_number to policy_applications table for tracking and audit trail
     */
    public function up(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('policy_applications', 'reference_number')) {
                $table->string('reference_number')->nullable()->unique()->after('status')->comment('Unique reference number for tracking');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            if (Schema::hasColumn('policy_applications', 'reference_number')) {
                $table->dropUnique('policy_applications_reference_number_unique');
                $table->dropColumn('reference_number');
            }
        });
    }
};
