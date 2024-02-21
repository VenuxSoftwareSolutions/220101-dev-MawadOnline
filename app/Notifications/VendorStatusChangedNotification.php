<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $oldStatus;
    protected $newStatus;
    protected $suspendedDetail ;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($oldStatus, $newStatus,$suspendedDetail=null)
    {
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->suspendedDetail=$suspendedDetail ;
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
            ->subject('Vendor Status Changed')
            ->view('seller.notification.email', [
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'logo' => $logo,
                'suspendedDetail' => $this->suspendedDetail
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
