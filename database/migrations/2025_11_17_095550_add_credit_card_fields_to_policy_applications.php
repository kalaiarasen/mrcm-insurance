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
        Schema::table('policy_applications', function (Blueprint $table) {
            // Add payment method column
            if (!Schema::hasColumn('policy_applications', 'payment_method')) {
                $table->enum('payment_method', ['proof', 'credit_card'])->nullable()->after('payment_document');
            }

            // Add cardholder name
            if (!Schema::hasColumn('policy_applications', 'card_holder_name')) {
                $table->string('card_holder_name')->nullable()->after('payment_method');
            }

            // Add last 4 digits of card (for reference only)
            if (!Schema::hasColumn('policy_applications', 'card_last_four')) {
                $table->string('card_last_four', 4)->nullable()->after('card_holder_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            if (Schema::hasColumn('policy_applications', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('policy_applications', 'card_holder_name')) {
                $table->dropColumn('card_holder_name');
            }
            if (Schema::hasColumn('policy_applications', 'card_last_four')) {
                $table->dropColumn('card_last_four');
            }
        });
    }
};
