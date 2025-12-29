<?php

namespace App\Mail;

use App\Models\PolicyApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PolicySubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $policyApplication;
    public $clientName;

    /**
     * Create a new message instance.
     */
    public function __construct(PolicyApplication $policyApplication)
    {
        $this->policyApplication = $policyApplication;
        
        // Get client name with title
        $profile = $policyApplication->user->applicantProfile;
        $title = $profile->title ?? '';
        $name = $policyApplication->user->name;
        
        // Remove title from name if it's already there
        $nameWithoutTitle = preg_replace('/^(DR\.|PROF\.|MR\.|MS\.|MRS\.)\s*/i', '', $name);
        
        $this->clientName = trim(ucfirst(strtolower($title)) . ' ' . $nameWithoutTitle);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), 'MRCM - Professional Indemnity')
                    ->subject('Application Successfully Submitted - MRCM Insurance')
                    ->view('emails.policy-submitted');
    }
}
