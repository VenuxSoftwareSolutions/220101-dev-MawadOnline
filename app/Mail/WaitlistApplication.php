<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WaitlistApplication extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $role;
    public $subscribeNewsletter;

    public function __construct($name, $email, $role, $subscribeNewsletter)
    {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        $this->subscribeNewsletter = $subscribeNewsletter;
    }

    public function build()
    {
        return $this
            ->subject('New Waitlist Submission')
            ->view('email.owner')
            ->with([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'subscribeNewsletter' => $this->subscribeNewsletter,
            ]);
    }
}
