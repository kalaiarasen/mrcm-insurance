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
            $table->string('certificate_document')->nullable()->after('payment_document')
                ->comment('Path to Certificate of Insurance (CI) PDF document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_applications', function (Blueprint $table) {
            $table->dropColumn('certificate_document');
        });
    }
};
