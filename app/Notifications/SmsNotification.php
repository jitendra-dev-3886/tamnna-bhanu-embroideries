<?php

namespace App\Notifications;

use App\Channels\MTalkZChannel;
use App\MTalkZ\MTalkZMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $is_otp_route;

    /**
     * Create a new notification instance.
     *
     * @param $message
     * @param $is_otp_route
     */
    public function __construct($message, $is_otp_route = false)
    {
        $this->message = $message;
        $this->is_otp_route = $is_otp_route;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [MTalkZChannel::class];
    }

    /**
     * Determine which queues should be used for each notification channel.
     *
     * @return array
     */
    public function viaQueues()
    {
        return [
            MTalkZChannel::class => $this->is_otp_route ? 'high' : 'default',
        ];
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param mixed $notifiable
     * @return MTalkZMessage
     */
    public function toMTalkZ($notifiable)
    {
        return (new MTalkZMessage())
            ->message($this->message)
            ->isOtpRoute($this->is_otp_route);
    }
}
