<?php

namespace Database\Seeders;

use App\Models\EmailSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailSetting::updateOrCreate(
            ['id' => 1],
            [
                'mail_new_policy' => env('MAIL_NEW_POLICY', 'nurmaisarahmasaaud@greateasterngeneral.com'),
                'mail_cc_uw' => env('MAIL_CC_UW', 'kampuiyee@greateasterngeneral.com,mrcm.agent@gmail.com,wanaisyaramli@greateasterngeneral.com'),
                'mail_from_uw' => env('MAIL_FROM_UW', 'insurance@mrcm.com.my'),
                'mail_from_name' => 'MRCM Insurance',
            ]
        );
    }
}
