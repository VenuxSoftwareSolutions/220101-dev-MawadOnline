<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ModificationRejectedNotification extends Notification
{
    use Queueable;
    protected $user;
    protected $rejectionReasons;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $rejectionReasons)
    {
        $this->user = $user;
        $this->rejectionReasons = $rejectionReasons;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Notify by email and web notification
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Profile Update Not Approved')
            ->view('emails.modification_rejected', [
                'user' => $this->user,
                'rejectionReasons' => $this->rejectionReasons,
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
            'message' => 'Profile update rejected. Please check email for reasons.',

        ];
    }
}
