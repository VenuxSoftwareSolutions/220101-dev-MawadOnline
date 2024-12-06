<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;

    public function __construct($order)
    {
       $this->order = $order;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Order Confirmation',
        );
    }

    public function content()
    {
        return new Content(
            view: 'email.order_confirmed',
            with: [
                "combined_order" => $this->order
            ]
        );
    }

    public function attachments()
    {
        return [];
    }
}
