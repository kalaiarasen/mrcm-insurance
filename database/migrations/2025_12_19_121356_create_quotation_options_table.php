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
        Schema::create('quotation_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_request_id')->constrained()->onDelete('cascade');
            $table->string('option_name'); // e.g., "Basic", "Standard", "Premium"
            $table->decimal('price', 10, 2);
            $table->text('details'); // Description of what's included
            $table->string('pdf_document')->nullable(); // Optional PDF file
            $table->timestamps();

            // Indexes
            $table->index('quotation_request_id');
        });

        // Add selected_option_id to quotation_requests table
        Schema::table('quotation_requests', function (Blueprint $table) {
            $table->foreignId('selected_option_id')->nullable()->after('admin_status')->constrained('quotation_options')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key first
        Schema::table('quotation_requests', function (Blueprint $table) {
            $table->dropForeign(['selected_option_id']);
            $table->dropColumn('selected_option_id');
        });

        Schema::dropIfExists('quotation_options');
    }
};
