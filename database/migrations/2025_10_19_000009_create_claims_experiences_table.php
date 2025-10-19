<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Create claims experience table (Step 6)
     */
    public function up(): void
    {
        Schema::create('claims_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Questions
            $table->string('claims_made')->nullable()->comment('yes, no');
            $table->string('aware_of_errors')->nullable()->comment('yes, no');
            $table->string('disciplinary_action')->nullable()->comment('yes, no');
            
            // Claim Details (when any above is yes)
            $table->date('claim_date_of_claim')->nullable();
            $table->date('claim_notified_date')->nullable();
            $table->string('claim_claimant_name')->nullable();
            $table->text('claim_allegations')->nullable();
            $table->decimal('claim_amount_claimed', 15, 2)->nullable();
            $table->string('claim_status')->nullable()->comment('outstanding, finalised');
            $table->decimal('claim_amounts_paid', 15, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims_experiences');
    }
};
