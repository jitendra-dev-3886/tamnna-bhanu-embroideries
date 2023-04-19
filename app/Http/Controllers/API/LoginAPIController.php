<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppLoginRequest;
use App\Http\Requests\AppLoginVerifyRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\DataTrueResource;
use \App\Http\Resources\LoginResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Traits\Smsable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;
use Laravel\Passport\Token;

/*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the User login, Change Password and logout Functionality.
    |
    */

class LoginAPIController extends Controller
{

    /**
     * Login user and create token
     *
     * @param LoginRequest $request
     * @return LoginResource|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = request(['contact_number', 'password']);
        if (!Auth::attempt($credentials))
            return User::GetError(config('constants.messages.user.invalid'));

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($user != null) {
            //get User Permission and save permission in token
            $role = Role::findorfail($user->role_id); //get role details
            $token->scopes = $user->role->permissions->pluck('name')->toArray();
            $token->save();
            $user->permissions = Permission::getPermissions($role);
            $user->authorization = $tokenResult->accessToken;
            User::addOrchangeLastLoginTime($user->id); // Add/Change last login time
            return new LoginResource($user);
        } else {
            return User::GetError("No User found.");
        }
    }

    /**
     * change password functionality.
     *
     * @param ChangePasswordRequest $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        //get all updated data.
        $data = $request->all();
        $masterUser = User::where('contact_number', $request->user()->contact_number)->first();
        if (Hash::check($data['old_password'], $masterUser->password)) {
            $masterData['password'] = bcrypt($data['new_password']);
            //update user password in master user table
            if ($masterUser->update($masterData))
                return new DataTrueResource($masterUser, config('constants.messages.password_changed'));
            else
                return User::GetError(config("constants.messages.something_wrong"));
        } else
            return User::GetError(config("constants.messages.invalid_old_password"));
    }

    /**
     * Logout User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json([
            'message' => 'You have been Successfully logged out!',
        ]);
    }

    /**
     * Login user and create token Send OTP.
     * Rocky Mehta
     * @param AppLoginRequest $request
     * @return LoginResource|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function appLogin(AppLoginRequest $request)
    {
        // $otpType  = $request->get('otpType'); // =>  No need for Tamanna  Project.
        $mobileNo = $request->get('contact_number'); // => Get Contact Number

        $user = User::where('contact_number', $mobileNo)->first(); // Search and Get result belongs to contact_number field.

        $user->otp = rand(100000, 999999); //generate a random 6 digit OTP code

        $otpVrfCd = $user->otp;
        $user->otp_verified_at = Carbon::now(); // => Rocky => Send OTP date and time
        $user->save();

        // Start development of SendOTP to contact_number.
        Smsable::sendOtp([$user], ['{{auto_otp_detection_code}}' => config('bw.mtalkz.auto_otp_detection_code')]); // Connection between Laravel and mTalkz
        return response()->json(
            [
                'message' => config('constants.messages.otp_success'),
                'data'    => (object)[]
            ],
            config('constants.validation_codes.ok')
        );
    }

    /**
     * Login user and create token
     *
     * @param AppLoginVerifyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function appLoginVerifyPortal(AppLoginVerifyRequest $request)
    {
        // $ctmInfluncr = CtmInfluncr::noLock()->where('inflCode', $request->get('inflCode'))->first();
        $mobileNo = $request->get('contact_number');
        $lytLoginUsr = User::where('contact_number', $request->get('contact_number'))
            ->where('otp', $request->get('otp'))
            ->where('otp_verified_at', '>=', Carbon::now()->addMinute(-10)) //OTP Valid for 10 minute
            ->first();

        if (is_null($lytLoginUsr))
            return User::GetError(config('constants.messages.invalid_mobile_vrf_code'));

        $randomPassword = User::getRandomPassword();
        $lytLoginUsr->update([
            'otp'             => null,
            'otp_verified_at' => null,
            'device_token'    => $request->get('device_token'),
            // 'password'        => bcrypt($randomPassword)
        ]);

        return User::GetMessage(true, config('constants.messages.login.success_portal'));
    }

    /**
     * Login user and create token
     *
     * @param AppLoginVerifyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function appLoginVerify(AppLoginVerifyRequest $request)
    {
        $mobileNo = $request->get('contact_number'); // => Get Contact Number

        $lytLoginUsr = User::where('contact_number', $mobileNo)
            ->where('otp', $request->get('otp'))
            ->where('otp_verified_at', '>=', Carbon::now()->addMinute(-10)) //OTP Valid for 10 minute
            ->first();
        // $user = User::where('contact_number', $mobileNo)->first();

        if (is_null($lytLoginUsr))
            return User::GetError(config('constants.messages.invalid_mobile_vrf_code'));

        // $oauthClient = Client::lock('WITH (NOLOCK)')->where('password_client', 1)->latest()->first();
        $oauthClient = Client::where('password_client', 1)->latest()->first();

        // print_r($oauthClient);
        // die(" = 187  ");
        if (is_null($oauthClient))
            return User::GetError('Oauth password client not found.');

        $randomPassword = User::getRandomPassword();

        //remove otp details from auth table & update device token
        $lytLoginUsr->update([
            // 'otp'             => null,
            // 'otp_verified_at' => null,
            'device_token'    => $request->get('device_token'),
            'password'        => bcrypt($randomPassword)
        ]);

        //get User Permission and save permission in token
        //$role = Role::findorfail($lytLoginUsr->usrRoleI); //get role details

        $data = [
            'username'      => $request->contact_number,
            'password'      => $randomPassword,
            'client_id'     => $oauthClient->id,
            'client_secret' => $oauthClient->secret,
            'grant_type'    => 'password',
        ];
        if (App::environment(['production'])) {
            User::revokeTokens($lytLoginUsr); // Revoking Tokens
        }

        $request = app('request')->create('/oauth/token', 'POST', $data);
        $tokenResult = json_decode(app()->handle($request)->getContent());
        // dump($tokenResult);
        // die(" = 218");
        $getToken = User::getUserActiveToken($lytLoginUsr->id, $oauthClient->id);
        // print_r($getToken);
        // die(" = 221");
        if ($getToken) {
            $getToken->scopes = [];
            $getToken->save(); // Update scope for user latest access token
        }

        $accessToken = "";
        $refreshToken = "";

        if ($tokenResult && isset($tokenResult->access_token) && isset($tokenResult->refresh_token)) {

            $accessToken = $tokenResult->access_token;
            $refreshToken = $tokenResult->refresh_token;

            $lytLoginUsr->authorization = $accessToken;
            $lytLoginUsr->refresh_token = $refreshToken;
        }

        User::addOrChangeLastLoginTime($lytLoginUsr->id); // Add/Change last login time

        // add successfully login attempt for historical data
        // $lytLoginUsr->lytLoginHst()->create([
        //     'remoteIP' => User::getIp(),
        //     'createId' => $lytLoginUsr->loginUId,
        //     'updateId' => $lytLoginUsr->loginUId,
        // ]);
        // dd(User::GetAuthMessage(new config('constants.messages.login.success'), $accessToken, $refreshToken) . " = 247");
        return User::GetAuthMessage(new $lytLoginUsr, config('constants.messages.login.success'), $accessToken, $refreshToken);
    }
}
