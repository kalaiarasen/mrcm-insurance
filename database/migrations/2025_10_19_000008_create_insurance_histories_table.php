<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Create insurance history table (Step 5)
     */
    public function up(): void
    {
        Schema::create('insurance_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Current Insurance
            $table->string('current_insurance')->nullable()->comment('yes, no');
            $table->string('insurer_name')->nullable();
            $table->string('period_of_insurance')->nullable();
            $table->decimal('policy_limit_myr', 15, 2)->nullable();
            $table->decimal('excess_myr', 15, 2)->nullable();
            $table->string('retroactive_date')->nullable();
            
            // Previous Claims
            $table->string('previous_claims')->nullable()->comment('yes, no');
            $table->longText('claims_details')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_histories');
    }
};
