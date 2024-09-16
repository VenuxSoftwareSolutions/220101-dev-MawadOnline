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
    protected $name ;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($oldStatus, $newStatus,$reason=null,$vendorEmail=null,$reasonTitle=null, $name=null)
    {
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->reason=$reason ;
        $this->vendorEmail=$vendorEmail ;
        $this->reasonTitle=$reasonTitle ;
        $this->name=$name ;

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
    // public function toMail($notifiable)
    // {
    //     $logo = asset('public/uploads/all/CxeI3PF3NMzjzHp6Ct3xf8dPS1q2pFYmwAwbHQii.png'); // Path to your custom logo
    //     $admin = User::where('user_type','admin')->first(); // Fetch the first admin
    //     $reasonTitle = $this->reasonTitle ;
    //     if (is_null($reasonTitle))
    //         $reasonTitle="Vendor Status Changed" ;
    //     return (new MailMessage)
    //         ->bcc($admin->email) // Blind copy (BCC) to admin
    //         ->subject($reasonTitle)
    //         ->view('seller.notification.email', [
    //             'oldStatus' => $this->oldStatus,
    //             'newStatus' => $this->newStatus,
    //             'logo' => $logo,
    //             'reason' => $this->reason,
    //             'vendorEmail' => $this->vendorEmail

    //         ]);
    // }
    public function toMail($notifiable)
    {
        $logo = asset('public/uploads/all/ldsr2INdMhRZ2Xq5vaEqX4YQsqJrzRxqVYl4R3MV.png'); // Path to your custom logo
        $admins = User::where('user_type', 'admin')->get(); // Fetch all admins

        $subject = $this->reasonTitle;
        if (is_null($subject)) {
            $subject = "Vendor Status Changed";
        }
        if ( $this->newStatus == 'Pending Approval') {
            $subject = "Registration Complete: You Account Is Now Pending Approval";

        }
        if ( $this->newStatus == 'Enabled') {
            $subject = "Welcome to MawadOnline  - Your Vendor Account Has Been Approved";

        }
        if ( $this->newStatus == 'Rejected') {
            $subject = "Vendor Registration Rejected";

        }
        if ( $this->newStatus == 'Pending Closure') {
            $subject = "Your account is pending closure.";

        }
        if ( $this->newStatus == 'Closed') {
            $subject = "Your Vendor Account Has Been Closed";

        }
        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->view('seller.notification.email', [
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'logo' => $logo,
                'reason' => $this->reason,
                'vendorEmail' => $this->vendorEmail,
                'vendorName' => $this->name
            ]);

        // Loop through each admin and add them as a BCC recipient
        foreach ($admins as $admin) {
            $mailMessage->bcc($admin->email);
        }

        return $mailMessage;
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
