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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('policy_application_id')->nullable();
            $table->enum('action', ['new', 'processing', 'closed', 'approved', 'rejected'])->default('new');
            $table->date('incident_date');
            $table->date('notification_date');
            $table->string('claim_title');
            $table->longText('claim_description')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, closed
            $table->decimal('claim_amount', 12, 2)->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('policy_application_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
