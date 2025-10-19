<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Create policy pricing table (Step 3)
     */
    public function up(): void
    {
        Schema::create('policy_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->date('policy_start_date')->nullable();
            $table->date('policy_expiry_date')->nullable();
            $table->decimal('liability_limit', 15, 2)->nullable();
            
            // Calculated pricing
            $table->decimal('base_premium', 10, 2)->nullable();
            $table->decimal('gross_premium', 10, 2)->nullable();
            $table->decimal('locum_addon', 10, 2)->nullable()->default(0);
            $table->decimal('sst', 10, 2)->nullable();
            $table->decimal('stamp_duty', 10, 2)->nullable()->default(10);
            $table->decimal('total_payable', 10, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_pricings');
    }
};
