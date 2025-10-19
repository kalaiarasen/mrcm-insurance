<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Create policy applications table (Step 7 & 8)
     */
    public function up(): void
    {
        Schema::create('policy_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Step 7: Data Protection
            $table->boolean('agree_data_protection')->default(false);
            
            // Step 8: Declaration & Signature
            $table->boolean('agree_declaration')->default(false);
            $table->longText('signature_data')->nullable()->comment('Base64 encoded PNG signature');
            
            // Status tracking
            $table->string('status')->default('draft')->comment('draft, submitted, approved, rejected');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_applications');
    }
};
