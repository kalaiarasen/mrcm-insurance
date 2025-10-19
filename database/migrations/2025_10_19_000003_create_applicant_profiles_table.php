<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Create applicant profile table (Step 1)
     */
    public function up(): void
    {
        Schema::create('applicant_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Information
            $table->string('title')->nullable()->comment('DR, MR, MS, PROF, etc');
            $table->string('nationality_status')->nullable()->comment('malaysian, non_malaysian');
            $table->string('nric_number')->nullable()->unique();
            $table->string('passport_number')->nullable()->unique();
            $table->string('gender')->nullable()->comment('male, female');
            
            // Registration Details
            $table->string('registration_council')->nullable();
            $table->string('other_council')->nullable();
            $table->string('registration_number')->nullable()->unique();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_profiles');
    }
};
