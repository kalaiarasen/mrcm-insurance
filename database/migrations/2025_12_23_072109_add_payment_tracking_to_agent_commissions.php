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
        Schema::table('agent_commissions', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('commission_amount')->comment('When commission was paid');
            $table->foreignId('payment_id')->nullable()->after('paid_at')->constrained('agent_payments')->onDelete('set null')->comment('Reference to payment record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_commissions', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
            $table->dropColumn(['paid_at', 'payment_id']);
        });
    }
};
