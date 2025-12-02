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
        Schema::table('quotation_requests', function (Blueprint $table) {
            // Drop old status column
            $table->dropColumn('status');
            
            // Add customer_status and admin_status like policy_applications
            $table->enum('customer_status', [
                'submitted',    // Initial submission
                'pay_now',      // Approved by admin, waiting for payment
                'paid',         // Payment proof uploaded
                'processing',   // Being processed
                'active',       // Active/completed
                'rejected',     // Rejected by admin
                'completed'     // Fully processed
            ])->default('submitted')->after('form_data');
            
            $table->enum('admin_status', [
                'new',          // New case
                'approved',     // Approved
                'send_uw',      // Send UW
                'active',       // Active
                'processing',   // Processing
                'rejected',     // Rejected
                'cancelled'     // Cancelled
            ])->default('new')->after('customer_status');
            
            // Add quotation/pricing fields
            $table->decimal('quoted_price', 10, 2)->nullable()->after('admin_status');
            $table->text('quotation_details')->nullable()->after('quoted_price');
            
            // Add payment fields (no credit card, only payment proof)
            $table->string('payment_document')->nullable()->after('quotation_details');
            $table->timestamp('payment_uploaded_at')->nullable()->after('payment_document');
            
            // admin_notes already exists, no need to add again
            
            // Add indexes for new status columns
            $table->index('customer_status');
            $table->index('admin_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_requests', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'customer_status',
                'admin_status',
                'quoted_price',
                'quotation_details',
                'payment_document',
                'payment_uploaded_at'
            ]);
            
            // Restore old status column
            $table->enum('status', ['pending', 'reviewed', 'quoted', 'rejected'])->default('pending');
        });
    }
};
