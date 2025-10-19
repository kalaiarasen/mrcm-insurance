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
        // Add application status to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('application_status')->default('draft')->nullable()->comment('draft, submitted, approved, rejected');
            $table->timestamp('application_submitted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['application_status', 'application_submitted_at']);
        });
    }
};
