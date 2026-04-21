<?php
// app/Mail/ConfirmationMail.php

namespace App\Mail;

use App\Models\Inscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✦ Ta place dans Renaît-Sens est sécurisée — ' . $this->user->libelleTraversee,
        );
    }
 public function content(): Content
    {
        return new Content(
            view: 'emails.confirmation',
            with: ['user' => $this->user], // ← passer $user à la vue
        );
    }
}