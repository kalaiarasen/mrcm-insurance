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
        Schema::table('quotation_requests', function (Blueprint $table) {
            $table->decimal('wallet_amount_applied', 10, 2)->default(0)->after('quoted_price');
            $table->decimal('final_price', 10, 2)->nullable()->after('wallet_amount_applied');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_requests', function (Blueprint $table) {
            $table->dropColumn(['wallet_amount_applied', 'final_price']);
        });
    }
};
