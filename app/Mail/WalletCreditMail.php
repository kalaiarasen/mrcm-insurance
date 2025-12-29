<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WalletCreditMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $amount;
    public $newBalance;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, float $amount, float $newBalance)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->newBalance = $newBalance;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💰 Wallet Credit Added - MRCM Insurance',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.wallet-credit',
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
