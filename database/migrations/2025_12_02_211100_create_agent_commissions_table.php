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
        Schema::create('agent_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('policy_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->decimal('commission_rate', 5, 2)->comment('Commission percentage (e.g., 10.00 = 10%)');
            $table->decimal('base_amount', 10, 2)->comment('Amount commission is calculated on (gross premium)');
            $table->decimal('commission_amount', 10, 2)->comment('Actual commission earned');
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('agent_id');
            $table->index('policy_application_id');
            $table->index('client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_commissions');
    }
};
