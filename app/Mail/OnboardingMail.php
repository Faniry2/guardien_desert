<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class OnboardingMail extends Mailable  
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $tempPassword) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue dans Renaît-Sens — Prépare ta traversée du désert !',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.onboarding',
            with: ['user' => $this->user, 'tempPassword' => $this->tempPassword], // ← passer $user et $tempPassword à la vue
        );
    }
}
