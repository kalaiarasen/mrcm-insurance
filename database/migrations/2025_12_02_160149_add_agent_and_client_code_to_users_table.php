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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('agent_id')->nullable()->after('loading')
                ->comment('Referring agent for this client');
            $table->string('client_code', 20)->unique()->nullable()->after('agent_id')
                ->comment('Unique client code format: #C{number}');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['agent_id', 'client_code']);
        });
    }
};
