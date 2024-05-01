<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorProfileChangesNotification extends Notification
{
    use Queueable;
    protected $vendor;
    protected $modifiedFields;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $vendor,$modifiedFields)
    {
        $this->vendor = $vendor;
        $this->modifiedFields = $modifiedFields;

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
        $vendorProfileUrl = route('vendor.registration.view',$this->vendor->id) ;

        return (new MailMessage)
            ->subject('Vendor Profile Changes Pending Approval')
            ->view('emails.vendor_profile_changes', [
                'vendor' => $this->vendor,
                'vendorProfileUrl' => $vendorProfileUrl,
                'modifiedFields' => $this->modifiedFields,

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
            'vendor_id' => $this->vendor->id,
            'vendor_name' => $this->vendor->name,
            'message' => 'Vendor profile changes are pending approval.',
        ];
    }
}
