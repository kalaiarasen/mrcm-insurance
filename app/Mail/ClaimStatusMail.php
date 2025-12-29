<?php

namespace App\Mail;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClaimStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $claim;
    public $oldStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Claim $claim, string $oldStatus = null)
    {
        $this->claim = $claim;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusTitle = match($this->claim->status) {
            'approved' => 'Claim Approved',
            'rejected' => 'Claim Rejected',
            'closed' => 'Claim Closed',
            default => 'Claim Status Updated'
        };

        return new Envelope(
            subject: "🔔 {$statusTitle} - Claim #{$this->claim->id}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.claim-status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
