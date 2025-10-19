<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Create risk management table (Step 4)
     */
    public function up(): void
    {
        Schema::create('risk_managements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('medical_records')->nullable()->comment('yes, no');
            $table->string('informed_consent')->nullable()->comment('yes, no');
            $table->string('adverse_incidents')->nullable()->comment('yes, no');
            $table->string('sterilisation_facilities')->nullable()->comment('yes, no');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_managements');
    }
};
