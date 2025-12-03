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
            $table->unsignedBigInteger('policy_application_id')->nullable()->after('user_id');
            $table->foreign('policy_application_id')->references('id')->on('policy_applications')->onDelete('cascade');
            $table->index('policy_application_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_pricings', function (Blueprint $table) {
            $table->dropForeign(['policy_application_id']);
            $table->dropIndex(['policy_application_id']);
            $table->dropColumn('policy_application_id');
        });
    }
};
