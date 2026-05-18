<?php

namespace App\Mail;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteToShareMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Note $note,
        public string $token,
        public string $recipientEmail
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Te invitan a ver la nota: {$this->note->title}",
        );
    }

    public function content(): Content
    {
        $url = route('shared.show', ['token' => $this->token], true);
        
        return new Content(
            view: 'emails.invite-share',
            with: [
                'note' => $this->note,
                'url' => $url,
                'recipientEmail' => $this->recipientEmail,
            ]
        );
    }
}
