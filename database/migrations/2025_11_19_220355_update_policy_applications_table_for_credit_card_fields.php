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
            // Remove old credit card fields if they exist
            if (Schema::hasColumn('policy_applications', 'card_holder_name')) {
                $table->dropColumn('card_holder_name');
            }
            if (Schema::hasColumn('policy_applications', 'card_last_four')) {
                $table->dropColumn('card_last_four');
            }
            
            // Add new credit card fields
            $table->string('name_on_card')->nullable()->after('payment_document');
            $table->string('nric_no')->nullable()->after('name_on_card');
            $table->string('card_no')->nullable()->after('nric_no');
            $table->string('card_issuing_bank')->nullable()->after('card_no');
            $table->json('card_type')->nullable()->after('card_issuing_bank')->comment('visa, master');
            $table->string('expiry_month')->nullable()->after('card_type');
            $table->string('expiry_year')->nullable()->after('expiry_month');
            $table->json('relationship')->nullable()->after('expiry_year')->comment('self, others, family_members');
            $table->boolean('authorize_payment')->default(false)->after('relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn([
                'name_on_card',
                'nric_no',
                'card_no',
                'card_issuing_bank',
                'card_type',
                'expiry_month',
                'expiry_year',
                'relationship',
                'authorize_payment'
            ]);
            
            // Restore old fields
            $table->string('card_holder_name')->nullable();
            $table->string('card_last_four')->nullable();
        });
    }
};
