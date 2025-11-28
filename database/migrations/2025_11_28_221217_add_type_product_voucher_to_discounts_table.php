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
        Schema::table('discounts', function (Blueprint $table) {
            $table->enum('type', ['discount', 'voucher'])->default('discount')->after('id');
            $table->enum('product', ['pharmacist', 'medical_practice', 'dental_practice'])->nullable()->after('type');
            $table->string('voucher_code', 50)->nullable()->unique()->after('product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn(['type', 'product', 'voucher_code']);
        });
    }
};
