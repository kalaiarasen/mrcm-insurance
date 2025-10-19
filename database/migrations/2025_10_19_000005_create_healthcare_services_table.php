<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Create healthcare services table (Step 2)
     */
    public function up(): void
    {
        Schema::create('healthcare_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('professional_indemnity_type')->nullable()->comment('medical_practice, dental_practice, pharmacist');
            $table->string('employment_status')->nullable()->comment('government, private, self_employed, non_practicing');
            $table->string('specialty_area')->nullable()->comment('general_practitioner, medical_specialist, etc');
            $table->string('cover_type')->nullable();
            $table->string('locum_practice_location')->nullable()->comment('private_clinic, private_hospital');
            $table->string('service_type')->nullable();
            $table->string('practice_area')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('healthcare_services');
    }
};
