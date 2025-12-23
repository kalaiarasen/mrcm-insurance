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
        Schema::create('agent_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->comment('Payment amount');
            $table->date('payment_date')->comment('Date of payment');
            $table->string('payment_method', 50)->comment('Payment method (e.g., Bank Transfer, Check, Cash)');
            $table->string('reference_number')->nullable()->comment('Payment reference/transaction ID');
            $table->text('notes')->nullable()->comment('Optional notes about the payment');
            $table->foreignId('created_by')->constrained('users')->comment('Admin who issued the payment');
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('agent_id');
            $table->index('payment_date');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_payments');
    }
};
