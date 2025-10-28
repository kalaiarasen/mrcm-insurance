<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add admin action fields to policy_applications table
     */
    public function up(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('policy_applications', 'remarks')) {
                $table->text('remarks')->nullable()->after('signature_data')->comment('Admin remarks/notes for approval or rejection');
            }
            if (!Schema::hasColumn('policy_applications', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('remarks')->constrained('users')->onDelete('set null')->comment('Admin who approved the application');
            }
            if (!Schema::hasColumn('policy_applications', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->after('approved_by')->constrained('users')->onDelete('set null')->comment('Admin who rejected the application');
            }
            if (!Schema::hasColumn('policy_applications', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('approved_at')->comment('Date and time when application was rejected');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            if (Schema::hasColumn('policy_applications', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
            if (Schema::hasColumn('policy_applications', 'rejected_by')) {
                $table->dropForeign(['rejected_by']);
                $table->dropColumn('rejected_by');
            }
            if (Schema::hasColumn('policy_applications', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('policy_applications', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};
