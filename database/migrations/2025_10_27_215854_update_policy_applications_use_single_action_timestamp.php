<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidate approved_at, rejected_at into single action_at
     * Consolidate approved_by, rejected_by into single action_by
     */
    public function up(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('policy_applications', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('policy_applications', 'rejected_by')) {
                $table->dropForeign(['rejected_by']);
                $table->dropColumn('rejected_by');
            }
            if (Schema::hasColumn('policy_applications', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
            
            // Add new consolidated columns
            if (!Schema::hasColumn('policy_applications', 'action_by')) {
                $table->foreignId('action_by')->nullable()->after('remarks')->constrained('users')->onDelete('set null')->comment('Admin who performed the last action');
            }
            if (!Schema::hasColumn('policy_applications', 'action_at')) {
                $table->timestamp('action_at')->nullable()->after('action_by')->comment('Date and time of last admin action');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            // Remove new columns
            if (Schema::hasColumn('policy_applications', 'action_at')) {
                $table->dropColumn('action_at');
            }
            if (Schema::hasColumn('policy_applications', 'action_by')) {
                $table->dropForeign(['action_by']);
                $table->dropColumn('action_by');
            }
            
            // Restore old columns
            if (!Schema::hasColumn('policy_applications', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('remarks')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('policy_applications', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->after('approved_by')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('policy_applications', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at');
            }
        });
    }
};
