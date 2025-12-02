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
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('remember_token');
            $table->date('date_of_birth')->nullable()->after('approval_status');
            $table->string('location')->nullable()->after('date_of_birth');
            $table->string('bank_account_number')->nullable()->after('location');
            $table->boolean('subscribe_newsletter')->default(false)->after('bank_account_number');
            $table->timestamp('approved_at')->nullable()->after('subscribe_newsletter');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'approval_status',
                'date_of_birth',
                'location',
                'bank_account_number',
                'subscribe_newsletter',
                'approved_at',
                'approved_by'
            ]);
        });
    }
};
