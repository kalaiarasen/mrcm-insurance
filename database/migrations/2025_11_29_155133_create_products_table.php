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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['car_insurance', 'rahmah_insurance', 'hiking_insurance', 'other'])->default('other');
            $table->text('coverage_benefits')->nullable();
            $table->string('brochure_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->string('pdf_title')->nullable()->default('Download Brochure');
            $table->json('form_fields')->nullable();
            $table->string('notification_email');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('type');
            $table->index('is_active');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
