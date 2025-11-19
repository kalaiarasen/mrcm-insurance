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
        Schema::table('claims_experiences', function (Blueprint $table) {
            $table->string('claim_date_of_claim')->nullable()->change();
            $table->string('claim_notified_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims_experiences', function (Blueprint $table) {
            $table->date('claim_date_of_claim')->nullable()->change();
            $table->date('claim_notified_date')->nullable()->change();
        });
    }
};
