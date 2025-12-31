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
        Schema::table('claims_experiences', function (Blueprint $table) {
            // Change decimal columns to varchar to accommodate formatted values like "RM 2000"
            $table->string('claim_amount_claimed')->nullable()->change();
            $table->string('claim_amounts_paid')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims_experiences', function (Blueprint $table) {
            // Revert back to decimal
            $table->decimal('claim_amount_claimed', 15, 2)->nullable()->change();
            $table->decimal('claim_amounts_paid', 15, 2)->nullable()->change();
        });
    }
};
