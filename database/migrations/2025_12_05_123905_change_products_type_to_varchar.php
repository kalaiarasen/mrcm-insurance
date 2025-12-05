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
        // Change type column from ENUM to VARCHAR
        DB::statement('ALTER TABLE products MODIFY COLUMN type VARCHAR(255) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to ENUM (optional - may cause data loss if new types were added)
        DB::statement("ALTER TABLE products MODIFY COLUMN type ENUM('car_insurance', 'rahmah_insurance', 'hiking_insurance', 'other') NOT NULL");
    }
};
