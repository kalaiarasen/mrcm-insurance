<?php

namespace App\Jobs;

use App\Mail\AnnouncementMail;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAnnouncementToClients implements ShouldQueue
{
    use Queueable;

    public $announcement;

    /**
     * Create a new job instance.
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all users with Client role
        $clients = User::whereHas('roles', function($query) {
            $query->where('name', 'Client');
        })->get();

        // Send email to each client
        foreach ($clients as $client) {
            try {
                Mail::to($client->email)->send(new AnnouncementMail($this->announcement));
                Log::info("Announcement email sent to: {$client->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send announcement to {$client->email}: " . $e->getMessage());
            }
        }

        Log::info("Announcement emails sent to " . $clients->count() . " clients");
    }
}
