<?php

namespace StuartPringle\Newsletter\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use StuartPringle\Newsletter\Models\MailingListSignup;

class ConfirmNewsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $signup;

    /**
     * Create a new message instance.
     */
    public function __construct(MailingListSignup $signup)
    {
        $this->signup = $signup;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('newsletter.confirmation_subject', 'Confirm Newsletter'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'newsletter::emails.confirm-newsletter',
            with: [
                'signup' => $this->signup,
            ],
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
