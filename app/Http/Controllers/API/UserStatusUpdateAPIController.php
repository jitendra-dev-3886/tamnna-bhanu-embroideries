<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserStatusUpdateRequest;

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

class UserStatusUpdateAPIController extends Controller
{

    /**
     * Email Verification
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(UserStatusUpdateRequest $request)
    {
        $user = User::find($request['user_id']);

        $user->user_status = config('constants.user.status_enum.active');
        $user->save();

        return redirect('');
    }
}
