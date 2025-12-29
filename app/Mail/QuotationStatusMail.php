<?php

namespace App\Mail;

use App\Models\QuotationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;
    public $status;

    /**
     * Create a new message instance.
     */
    public function __construct(QuotationRequest $quotation, string $status)
    {
        $this->quotation = $quotation;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusTitle = match($this->status) {
            'quote' => 'Quote Provided',
            'declined' => 'Application Declined',
            'active' => 'Policy Activated',
            default => 'Status Updated'
        };

        return new Envelope(
            subject: "📋 {$statusTitle} - {$this->quotation->product->title}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation-status',
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
