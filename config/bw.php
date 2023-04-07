<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Service Provider : https://mtalkz.com/
    |--------------------------------------------------------------------------
    |
    | mTalkZ account credentials will be required to access the API documentation
    | https://msg.mtalkz.com/main.php?page=apidoc&u=u0
    |
    */

    'mtalkz' => [
        'api_key'                 => env('MTALKZ_API_KEY'),
        'api_key_for_otp'         => env('MTALKZ_API_KEY_FOR_OTP'),
        'sender_id'               => env('MTALKZ_SENDER_ID'),
        'base_url'                => 'https://msg.mtalkz.com/V2',
        'send_sms_path'           => '/http-api-post.php',
        'auto_otp_detection_code' => env('AUTO_OTP_DETECTION_CODE'),
    ],

    /*
   |--------------------------------------------------------------------------
   | Bank, Aadhar, Pan, UPI Verification : https://surepass.io/
   |--------------------------------------------------------------------------
   |
   | Sure Pass Technologies exposes RESTful API for following verification :-
   | https://documentation.surepass.io/
   |
   | Aadhar Card :-
   | Send OTP (registered Mobile against given Aadhar Number) : https://documentation.surepass.io/#dd73cf15-0568-4253-8bc1-09618bfa98cf
   | To Verify OTP (for the Aadhar Number) : https://documentation.surepass.io/#dec48a58-48a8-475a-b786-c9f9cbac34f4
   |
   | Pan Card :-
   | Pan Lite : https://documentation.surepass.io/#08608679-90e2-4414-913e-d1b0691bf8a9
   | Request the API with Pan Card and it will return the account details
   |
   | Bank Details :-
   | Bank Details Verification : https://documentation.surepass.io/#a75c82d0-0648-4c65-a4c3-345e07612ae4
   |
   | UPI Details :-
   | UPI Details : https://documentation.surepass.io/#92e81eb5-f556-49a5-bebe-7d76094547cf
   |
   */

    'surepass'           => [
        'api_key'                  => env('SUREPASS_API_KEY'),
        'base_url'                 => env('SUREPASS_BASE_URL'),
        'pan_path'                 => '/pan/pan',
        'aadhar_generate_otp_path' => '/aadhaar-v2/generate-otp',
        'aadhar_verify_otp_path'   => '/aadhaar-v2/submit-otp',
        'bank_path'                => '/bank-verification',
        'ocr_aadhar'               => '/ocr/aadhaar',
        'upi_path'                 => '/bank-verification/upi-verification',
    ],

    'cash_transfer' => [
        'threshold_amount' => env('THRESHOLD_AMOUNT'),
        'cashfree_payout_url' => env('CASHFREE_PAYOUT_URL'),
        'cashfree_payout_client_id' => env('CASHFREE_PAYOUT_CLIENT_ID'),
        'cashfree_payout_client_secret' => env('CASHFREE_PAYOUT_CLIENT_SECRET'),
    ],
    'voiceotp' => [
        'voice_domain' => 'http://voice.httpapi.zone:9501/voiceapi/api_otp.php?',
        'account' => 'BirlaWhiteTr_Mv',
        'pin' => 'tI(7lcpd',
        'campaignid' => '421377',


    ],

    'program_unique_key' => 'birla'
];
