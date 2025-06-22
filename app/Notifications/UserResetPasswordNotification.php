<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // We will use a custom channel, but need to return something.
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // This method is not used, but is required by the interface.
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Send the password reset notification.
     *
     * @param  mixed  $notifiable
     * @return void
     */
    public function send($notifiable)
    {
        $resetUrl = url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false));

        try {
            $subject = 'Reset Your Password';
            $body = "Dear user,\n\nPlease click the following link to reset your password: {$resetUrl}\n\nThis link will expire in 60 minutes. If you did not request a password reset, no further action is required.\n\nBest Regards,\nThe Autochain Nexus Team";
            
            Http::post('http://localhost:8082/api/v1/send-email', [
                'to' => $notifiable->getEmailForPasswordReset(),
                'subject' => $subject,
                'body' => $body,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to call email service for password reset of {$notifiable->getEmailForPasswordReset()}: " . $e->getMessage());
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
