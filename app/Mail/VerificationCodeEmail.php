<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $userName;
    public $minutes;

    public $verificationCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verificationCode, $userName, $minutes = 10)
    {
        $this->verificationCode = $verificationCode;
        $this->userName = $userName;
        $this->minutes = $minutes;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Your MawadOnline OTP',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.verification_code',
            with: [
                'userName' => $this->userName,
                'verificationCode' => $this->verificationCode,
                'minutes' => $this->minutes
            ]
    
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
