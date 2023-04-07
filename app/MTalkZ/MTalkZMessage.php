<?php

namespace App\MTalkZ;

class MTalkZMessage
{
    public $message;
    public $is_otp_route;

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function isOtpRoute($is_otp_route)
    {
        $this->is_otp_route = $is_otp_route;
        return $this;
    }
}
