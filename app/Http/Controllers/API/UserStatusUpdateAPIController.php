<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserStatusUpdateRequest;
use App\Http\Resources\UserResource;

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
        $data = $request->all();
        $user = User::find($data['user_id']);
        $user->update($data);
        
        return \App\Models\User::GetMessage(new UserResource($user), config('constants.messages.update_success'));
    }
}
