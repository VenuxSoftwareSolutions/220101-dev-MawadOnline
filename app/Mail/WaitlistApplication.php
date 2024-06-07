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
    public $phone;
    public $work;
    public $info;
    public $subscribeNewsletter;

    public function __construct($name, $email ,$phone ,$work  ,$info, $subscribeNewsletter )
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->work = $work;
        $this->info = $info;
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
                'phone' => $this->phone,
                'work' => $this->work,
                'info' => $this->info,
                'subscribeNewsletter' => $this->subscribeNewsletter,
            ]);
    }
}
