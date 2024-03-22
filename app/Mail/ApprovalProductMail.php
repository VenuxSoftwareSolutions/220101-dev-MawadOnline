<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalProductMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $status;
    public $text;

    public function __construct($status, $text)
    {
        $this->status = $status;
        $this->text = $text;
    }
    
    public function build()
    {
        return $this
            ->subject($this->status)
            ->view('emails.approval_product')
            ->with([
                'text' => $this->text,
            ]);
    }
}
