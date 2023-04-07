<?php

namespace App\Notifications;

use App\MTalkZ\MTalkZMessage;
use App\Channels\MTalkZChannel;
use Illuminate\Notifications\Notification;

class SmsNotificationWithoutQueue extends Notification
{

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
     * Get the sms representation of the notification.
     *
     * @param mixed $notifiable
     * @return MTalkZMessage
     */
    public function toMTalkZ($notifiable)
    {
        return (new MTalkZMessage())->message($this->message)
            ->isOtpRoute($this->is_otp_route);
    }
}
