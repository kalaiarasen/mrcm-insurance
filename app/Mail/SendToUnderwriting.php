<?php

namespace App\Mail;

use App\Models\PolicyApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class SendToUnderwriting extends Mailable
{
    use Queueable, SerializesModels;

    public $policyApplication;
    public $profile;
    public $healthcare;
    public $pricing;

    /**
     * Create a new message instance.
     */
    public function __construct(PolicyApplication $policyApplication)
    {
        $this->policyApplication = $policyApplication;
        $this->profile = $policyApplication->user->applicantProfile;
        $this->healthcare = $policyApplication->user->healthcareService;
        $this->pricing = $policyApplication->user->policyPricing;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Determine if it's renewal or new application
        $type = $this->policyApplication->submission_version > 1 ? 'Renewal' : 'New Application';
        
        // Get professional indemnity type
        $piType = $this->healthcare ? ucfirst(str_replace('_', ' ', $this->healthcare->professional_indemnity_type)) : 'N/A';
        
        // Get applicant name
        $name = strtoupper($this->policyApplication->user->name ?? 'N/A');
        
        // Create subject
        $subject = sprintf(
            '[%s] [%s] – GEGM Professional Indemnity – [%s] %s',
            $this->policyApplication->reference_number ?? 'MRCM#' . $this->policyApplication->id,
            $type,
            $piType,
            $name
        );

        // Get CC emails from env
        $ccEmails = array_filter(array_map('trim', explode(',', env('MAIL_CC_UW', ''))));

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(
                env('MAIL_FROM_UW', 'insurance@mrcm.com.my'),
                'MRCM Insurance'
            ),
            to: [env('MAIL_NEW_POLICY', 'nurmaisarahmasaaud@greateasterngeneral.com')],
            // cc: $ccEmails,
            subject: $subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.send-to-underwriting',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generate PDF
        $pdf = Pdf::loadView('pdf.policy-application', [
            'policyApplication' => $this->policyApplication,
            'profile' => $this->profile,
            'qualifications' => $this->policyApplication->user->qualifications,
            'addresses' => $this->policyApplication->user->addresses,
            'contact' => $this->policyApplication->user->applicantContact,
            'healthcare' => $this->healthcare,
            'pricing' => $this->pricing,
            'risk' => $this->policyApplication->user->riskManagement,
            'insurance' => $this->policyApplication->user->insuranceHistory,
            'claims' => $this->policyApplication->user->claimsExperience,
        ]);

        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $filename = 'Policy_Application_' . ($this->policyApplication->reference_number ?? 'MRCM#' . $this->policyApplication->id) . '.pdf';

        return [
            Attachment::fromData(fn () => $pdf->output(), $filename)
                ->withMime('application/pdf'),
        ];
    }
}
