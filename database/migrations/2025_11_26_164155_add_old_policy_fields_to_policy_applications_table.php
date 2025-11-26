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
            // Old system tracking fields
            $table->integer('old_policy_id')->nullable()->after('id');
            $table->string('old_policy_uuid', 100)->nullable()->after('old_policy_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            $table->dropColumn(['old_policy_id', 'old_policy_uuid']);
        });
    }
};
