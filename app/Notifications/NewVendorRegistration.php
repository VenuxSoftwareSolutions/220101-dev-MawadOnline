<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVendorRegistration extends Notification implements ShouldQueue
{
    use Queueable;
    protected $vendor ;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($vendor)
    {
        $this->vendor = $vendor ;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $logo = asset('public/uploads/all/CxeI3PF3NMzjzHp6Ct3xf8dPS1q2pFYmwAwbHQii.png'); // Path to your custom logo

        return (new MailMessage)
        ->subject('New Vendor Registration Request')
        ->view('emails.vendor_registration_notification', [
            'vendorName' => $this->vendor->name,
            'logo' => $logo,
            'vendorEmail' => $this->vendor->email,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
