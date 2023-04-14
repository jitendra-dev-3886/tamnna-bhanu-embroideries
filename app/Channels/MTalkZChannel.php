<?php

namespace App\Channels;

// use App\Models\LymInfluncr;
use App\Models\User;
use App\Models\SmsCommunicationHistory;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class MTalkZChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {

        $message = $notification->toMTalkZ($notifiable); // MTalkZMessage object

        $number  = $notifiable->routeNotificationForMTalkZ(); // Get contact_number from Users model.
        $unicode = config('constants.unicode.unicode_value'); //regional language unicode

        //send sms via mTalkZ API
        $response = [
            "apikey"   => $message->is_otp_route ? config('bw.mtalkz.api_key_for_otp') : config('bw.mtalkz.api_key'),
            "senderid" => config('bw.mtalkz.sender_id'),
            "format"   => "json",
            "number"   => $number, //to mobile number
            "message"  => $message->message, // actual sms message
        ];

        Http::post(config('bw.mtalkz.base_url') . config('bw.mtalkz.send_sms_path'), $response);
    }
}
