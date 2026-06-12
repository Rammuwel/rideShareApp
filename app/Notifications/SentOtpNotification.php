<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;

class SentOtpNotification extends Notification
{
    use Queueable;

     public $login_code;
    /**
     * Create a new notification instance.
     */
    public function __construct($login_code)
    {
        //
        $this->login_code = $login_code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [TwilioChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toTwilio($notifiable)
    {
        $notifiable->update(['login_code' => $this->login_code]);
        return (new TwilioSmsMessage())
            ->content("Your OTP is: {$this->login_code}. Do not share this code.");
    }

    public function toArray(object $notifiable): array
    {
        return [
            
        ];
    }

   
}
