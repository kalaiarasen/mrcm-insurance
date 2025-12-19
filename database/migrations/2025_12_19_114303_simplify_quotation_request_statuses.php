<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Change enum columns to VARCHAR temporarily to allow data updates
        DB::statement("ALTER TABLE quotation_requests MODIFY COLUMN customer_status VARCHAR(50)");
        DB::statement("ALTER TABLE quotation_requests MODIFY COLUMN admin_status VARCHAR(50)");

        // Step 2: Migrate existing data to new status values
        DB::statement("
            UPDATE quotation_requests 
            SET admin_status = CASE 
                WHEN admin_status IN ('new', 'rejected', 'cancelled') THEN 'new'
                WHEN admin_status IN ('approved', 'send_uw', 'processing') THEN 'quote'
                WHEN admin_status IN ('active') THEN 'active'
                ELSE 'new'
            END
        ");

        DB::statement("
            UPDATE quotation_requests 
            SET customer_status = CASE 
                WHEN customer_status IN ('submitted', 'rejected') THEN 'new'
                WHEN customer_status IN ('pay_now', 'paid', 'processing') THEN 'quote'
                WHEN customer_status IN ('active', 'completed') THEN 'active'
                ELSE 'new'
            END
        ");

        // Step 3: Convert back to ENUM with new values
        DB::statement("
            ALTER TABLE quotation_requests 
            MODIFY COLUMN customer_status ENUM('new', 'quote', 'active') NOT NULL DEFAULT 'new'
        ");

        DB::statement("
            ALTER TABLE quotation_requests 
            MODIFY COLUMN admin_status ENUM('new', 'quote', 'active') NOT NULL DEFAULT 'new'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Change to VARCHAR temporarily
        DB::statement("ALTER TABLE quotation_requests MODIFY COLUMN customer_status VARCHAR(50)");
        DB::statement("ALTER TABLE quotation_requests MODIFY COLUMN admin_status VARCHAR(50)");

        // Step 2: Restore old enum values
        DB::statement("
            ALTER TABLE quotation_requests 
            MODIFY COLUMN customer_status ENUM('submitted', 'pay_now', 'paid', 'processing', 'active', 'rejected', 'completed') NOT NULL DEFAULT 'submitted'
        ");

        DB::statement("
            ALTER TABLE quotation_requests 
            MODIFY COLUMN admin_status ENUM('new', 'approved', 'send_uw', 'active', 'processing', 'rejected', 'cancelled') NOT NULL DEFAULT 'new'
        ");
    }
};
