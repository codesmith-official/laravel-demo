<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $token,
    ) {}

    public function resetUrl(): string
    {
        return route('password.reset', [
            'token' => $this->token,
            'email' => $this->user->email,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reset your password');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-link',
            with: ['resetUrl' => $this->resetUrl()],
        );
    }
}
