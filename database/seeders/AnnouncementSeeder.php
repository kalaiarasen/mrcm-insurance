<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $announcements = [
            [
                'title' => 'System Maintenance Notice',
                'description' => 'We will be performing scheduled maintenance on our systems from 2:00 AM to 6:00 AM on Saturday. During this time, some services may be temporarily unavailable. We apologize for any inconvenience.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'New Policy Features Available',
                'description' => 'We are excited to announce new features in our insurance policies including enhanced coverage options, faster claim processing, and improved customer support services. Contact our team to learn more about how these updates benefit you.',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'title' => 'Holiday Office Hours',
                'description' => 'Please note that our offices will be closed on December 25th and January 1st for the holidays. Emergency claims can still be filed through our 24/7 online portal or mobile app.',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'title' => 'Important Security Update',
                'description' => 'For your security, we have implemented additional authentication measures. Please ensure your account information is up to date and consider enabling two-factor authentication for added protection.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
