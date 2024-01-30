<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificarUsuario extends Mailable
{
    use Queueable, SerializesModels;
    public User $user_data;
    public string $code;
    public string $id;
    public string $email;
    /**
     * Create a new message instance.
     */
    public function __construct($user_data)
    {
        $this->user_data = $user_data;
        $this->code = $user_data->second_factory_token;
        $this->id = $user_data->id;
        $this->email = $user_data->email;
    }

    
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Codigo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'codigo',
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
