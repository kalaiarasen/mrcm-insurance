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
        Schema::table('qualifications', function (Blueprint $table) {
            // Change year_obtained from YEAR type to VARCHAR(50) to handle invalid data like "OKTOBER 1993"
            $table->string('year_obtained', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qualifications', function (Blueprint $table) {
            // Revert back to YEAR type
            $table->year('year_obtained')->nullable()->change();
        });
    }
};
