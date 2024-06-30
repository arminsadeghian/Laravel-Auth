<?php

namespace App\Mail;

use App\Models\LoginToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMagicLink extends Mailable
{
    use Queueable, SerializesModels;

    public LoginToken $token;
    public array $options;

    /**
     * Create a new message instance.
     */
    public function __construct(LoginToken $token, array $options)
    {
        $this->token = $token;
        $this->options = $options;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Magic Link',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.magic-link',
            with: [
                'link' => $this->generateLink()
            ]
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

    private function generateLink()
    {
        return route('auth.magic.login', [
                'token' => $this->token->token
            ] + $this->options);
    }
}
