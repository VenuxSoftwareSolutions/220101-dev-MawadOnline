<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SellerStaffMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $role;
    public $password;
    public $vendor;
    public $url;

    public function __construct($user, $role, $password, $vendor,$url)
    {
        $this->user = $user;
        $this->vendor = $vendor;
        $this->role = $role;
        $this->password = $password;
        $this->url = $url;
    }

    public function build()
    {
        return $this
            ->subject('New mawadonline vendor staff account')
            ->view('emails.seller_staff')
            ->with([
                'vendor' => $this->vendor,
                'user' => $this->user,
                'role' => $this->role,
                'url' => $this->url,
                'password' => $this->password,
            ]);
    }
}
