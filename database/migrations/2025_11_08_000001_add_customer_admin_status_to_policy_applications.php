<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add customer_status and admin_status fields
     */
    public function up(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            // Customer Status (C.S) - What customer sees
            $table->string('customer_status')
                ->default('submitted')
                ->after('status')
                ->comment('submitted, pay_now, paid, processing, active');
            
            // Admin Status (A.S) - What admin manages
            $table->string('admin_status')
                ->default('new_case')
                ->after('customer_status')
                ->comment('new_case, new_renewal, not_paid, paid, sent_uw, active');
            
            // Add payment related timestamps
            $table->timestamp('payment_received_at')->nullable()->after('admin_status');
            $table->timestamp('sent_to_underwriter_at')->nullable()->after('payment_received_at');
            $table->timestamp('activated_at')->nullable()->after('sent_to_underwriter_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            $table->dropColumn([
                'customer_status',
                'admin_status',
                'payment_received_at',
                'sent_to_underwriter_at',
                'activated_at'
            ]);
        });
    }
};
