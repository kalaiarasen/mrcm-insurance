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
        Schema::table('policy_pricings', function (Blueprint $table) {
            $table->decimal('loading_percentage', 5, 2)->nullable()->default(0)->after('base_premium')
                ->comment('Loading percentage applied to base premium');
            $table->decimal('loading_amount', 10, 2)->nullable()->default(0)->after('loading_percentage')
                ->comment('Loading amount calculated from base premium');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_pricings', function (Blueprint $table) {
            $table->dropColumn(['loading_percentage', 'loading_amount']);
        });
    }
};
