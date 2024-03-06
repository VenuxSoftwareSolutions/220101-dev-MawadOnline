<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $oldStatus;
    protected $newStatus;
    protected $reason ;
    protected $vendorEmail ;
    protected $reasonTitle ;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($oldStatus, $newStatus,$reason=null,$vendorEmail=null,$reasonTitle=null)
    {
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->reason=$reason ;
        $this->vendorEmail=$vendorEmail ;
        $this->reasonTitle=$reasonTitle ;

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
        $admin = User::where('user_type','admin')->first(); // Fetch the first admin
        $reasonTitle = $this->reasonTitle ;
        if (is_null($reasonTitle))
            $reasonTitle="Vendor Status Changed" ;
        return (new MailMessage)
            ->bcc($admin->email) // Blind copy (BCC) to admin
            ->subject($reasonTitle)
            ->view('seller.notification.email', [
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'logo' => $logo,
                'reason' => $this->reason,
                'vendorEmail' => $this->vendorEmail

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
