<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $oldStatus;
    protected $newStatus;
    protected $suspendedTitle ;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($oldStatus, $newStatus,$suspendedTitle=null)
    {
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->suspendedTitle = $suspendedTitle;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $oldStatus = $this->oldStatus;
        $newStatus = $this->newStatus;

        switch ($newStatus) {
            case 'Suspended':
                $message = __('messages.suspended');
                break;
            case 'Closed':
                $message = __('messages.vendor_closed');
                break;
            case 'Pending Closure':
                $message = __('messages.pending_closure');
                break;
            case 'Enabled':
                $message = __('messages.approved');
                break;
            case 'Rejected':
                $message = __('messages.registration_rejected');
                break;
            default:
                $message = 'Your vendor status has been changed from ' . $oldStatus . ' to ' . $newStatus . '.';
                break;
        }

        return (new MailMessage)
            ->subject('Vendor Status Changed')
            ->line($message)
            ->line('Thank you for using Mawad Online!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $oldStatus = $this->oldStatus;
        $newStatus = $this->newStatus;
        $suspendedTitle = $this->suspendedTitle;
        switch ($newStatus) {
            case 'Suspended':
                $message = __('messages.suspended');
                break;
            case 'Closed':
                $message = __('messages.vendor_closed');
                break;
            case 'Pending Closure':
                $message = __('messages.pending_closure');
                break;
            case 'Enabled':
                $message = __('messages.approved');
                break;
            case 'Rejected':
                $message = __('messages.registration_rejected');
                break;
            case 'Pending Approval':
                $message = __('messages.registration_completed');
                break;
            default:
                $message = 'Your vendor status has been changed from ' . $oldStatus . ' to ' . $newStatus . '.';
                break;
        }

        return [
            'message' => $message,
            'newStatus' =>$newStatus,
            'suspendedTitle' =>$suspendedTitle
        ];
    }
}
