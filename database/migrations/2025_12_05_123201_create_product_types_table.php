<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'car_insurance'
            $table->string('display_name'); // e.g., 'Car Insurance'
            $table->timestamps();
        });

        // Seed initial product types
        DB::table('product_types')->insert([
            [
                'name' => 'car_insurance',
                'display_name' => 'Car Insurance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'rahmah_insurance',
                'display_name' => 'Rahmah Insurance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'hiking_insurance',
                'display_name' => 'Hiking Insurance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'other',
                'display_name' => 'Other',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_types');
    }
};
