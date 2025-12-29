<?php

namespace App\Mail;

use App\Models\PolicyApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PolicyActiveMail extends Mailable
{
    use Queueable, SerializesModels;

    public $policyApplication;
    public $clientName;
    public $portalUrl;
    public $certificateUrl;

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
        
        $this->clientName = trim(ucfirst(strtolower($title)) . '. ' . $nameWithoutTitle);
        $this->portalUrl = 'https://insurance.mrcm.com.my';
        
        // Generate certificate download URL if available
        if ($policyApplication->certificate_document) {
            $this->certificateUrl = asset('storage/' . $policyApplication->certificate_document);
        } else {
            $this->certificateUrl = null;
        }
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), 'MRCM - Professional Indemnity')
                    ->subject('Certificate of Insurance Issued - MRCM Insurance')
                    ->view('emails.policy-active');
    }
}
