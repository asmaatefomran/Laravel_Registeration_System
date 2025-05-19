<?php

namespace App\Mail;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New registered user',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome', 
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
