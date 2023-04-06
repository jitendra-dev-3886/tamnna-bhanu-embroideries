<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/*
    |--------------------------------------------------------------------------
    | Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling verify and resend requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

class VerificationAPIController extends Controller
{

    /**
     * Email Verification
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request) {
        $user = User::find($request['id']);
        $user->email_verified_at = config('constants.calender.date_time'); // to enable the “email_verified_at field of that user be a current time stamp user must verify email feature
        $user->save();

        return redirect('');

    }

    /**
     * Resend the email verification notification.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return User::GetError('User already have verified email!');
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json('The notification has been resubmitted');
    }
}
