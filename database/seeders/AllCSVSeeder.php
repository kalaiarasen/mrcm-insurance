<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AllCSVSeeder extends Seeder
{
    /**
     * Run all seeders in order.
     */
    public function run(): void
    {
        $this->call([
            UsersOldDataSeeder::class,          // TBL_Users_MST.csv
            TBLMemberMSTSeeder::class,   // TBL_Member_MST.csv
            TBLPolicyMSTSeeder::class,   // TBL_Policy_MST.csv
            ExcelDentalSeeder::class,         // Excel_Dental.csv
            ExcelMedicalSeeder::class,        // Excel_Medical.csv
            ExcelPharmacistSeeder::class,     // Excel_Pharmacist.csv
            ExcelPolicyDetailsSeeder::class,  // Excel_PolicyDetails.csv
        ]);
    }
}
