<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Scopes;
use App\Http\Resources\UserResource;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\ActivityResource;
use App\Traits\UploadTrait;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use App\Traits\Legendable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, Scopes, SoftDeletes, HasFactory, UploadTrait, Legendable;

    /**
     * @var array
     */
    protected $fillable = ['id', 'role_id', 'name', 'company_name', 'city', 'email', 'contact_number', 'otp', 'otp_verified_at', 'device_token', 'user_status', 'password'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = ['id', 'contact_number', 'role->name', 'user_status', 'last_login'];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = ['role'];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = ['id', 'contact_number'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = ['orders', 'carts'];

    /**
     * @var array
     */
    public $sortable = ['users.created_at', 'users.id', 'contact_number'];

    /**
     * @var array
     */
    public $foreign_sortable = ['role_id'];

    /**
     * @var array
     */
    public $foreign_table = ['roles'];

    /**
     * @var array
     */
    public $foreign_key = ['name'];

    /**
     * @var array
     */
    public $foreign_method = ['role'];

    /**
     * @var array
     */
    public $type_sortable = ['user_status'];

    /**
     * @var array
     */
    public $type_enum = [
        ['constants.users.user_status_enum.inactive', 'constants.users.user_status_enum.active']
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
        ['constants.users.user_status.0', 'constants.users.user_status.1']
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'device_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

        'id' => 'string',
        'contact_number' => 'string',
        'role_id' => 'string',
        'otp_verified_at' => 'string',
        'device_token' => 'string',
        'user_status' => 'string',

    ];



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }



    public function scopeCommonFunctionMethod($query, $model, $request, $preQuery = null, $tablename = null, $groupBy = null, $export_select = false, $no_paginate = false)
    {
        return $this->getCommonFunctionMethod($model, $request, $preQuery, $tablename, $groupBy, $export_select, $no_paginate);
    }

    public static function getCommonFunctionMethod($model, $request, $preQuery = null, $tablename = null, $groupBy = null, $export_select = false, $no_paginate = false)
    {
        if (is_null($preQuery)) {
            $mainQuery = $model::withSearch($request->get('search'), $export_select);
        } else {
            $mainQuery = $model->withSearch($request->get('search'), $export_select);
        }
        if ($request->filled('filter') != '')
            $mainQuery = $mainQuery->withFilter($request->get('filter'));
        if (!is_null($groupBy))
            $mainQuery = $mainQuery->groupBy($groupBy);
        if ($no_paginate) {
            return $mainQuery->withOrderBy($request->get('sort'), $request->get('order_by'), $tablename, $export_select, $groupBy);
        } else {

            $mainQuery = $mainQuery->withOrderBy($request->get('sort'), $request->get('order_by'), $tablename, $export_select, $groupBy);

            if ($request->filled('per_page'))
                return $mainQuery->withPerPage($request->get('per_page'));
            else
                return $mainQuery->withPerPage($mainQuery->count());
        }
    }

    /**
     * Common Display Error Message.
     *
     * @param $query
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function GetError($message)
    {
        return response()->json(['message' => $message, 'errors' => (object)[]], config('constants.validation_codes.unassigned'));
    }

    /**
     *  Common Display Messsage Response.
     *
     * @param $resource
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function GetMessage($resource, $message)
    {

        return response()->json([
            'message' => $message,
            'data' => $resource,
        ]);
    }

    /**
     * Add or Change Last Login Time
     *
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeAddOrChangeLastLoginTime($query, $id)
    {

        return $query->where('id', $id)->update([
            'last_login' => Carbon::now()->format('Y-m-d H:i:s')
            // 'last_seen_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Add User
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateUser($query, $request)
    {

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $data['user_status'] = config('constants.user.user_status_enum.inactive');
        $user = User::create($data);



        return \App\Models\User::GetMessage(new UserResource($user), config('constants.messages.create_success'));
    }

    /**
     * Update User
     * @param $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateUser($query, $request, $user)
    {
        $data = $request->all();


        $user->update($data);

        return \App\Models\User::GetMessage(new UserResource($user), config('constants.messages.update_success'));
    }

    /**
     * Delete User
     *
     * @param Request $request
     * @param User $user
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteUser($query, $request, $user)
    {

        if ($user->id == config('constants.system_user_id'))
            return User::GetError(config('constants.messages.admin_user_delete_error'));



        $user->delete();

        return new DataTrueResource($user, config('constants.messages.delete_success'));
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query, $request)
    {
        if (!empty($request->id)) {

            if (in_array(config('constants.system_user_id'), $request->id))
                return User::GetError(config('constants.messages.admin_user_delete_error'));

            User::whereIn('id', $request->id)->get()->each(function ($user) {


                $user->delete();
            });



            return new DataTrueResource(true, config('constants.messages.delete_success'));
        } else {
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }

    /**
     *  Specifies the users's Contact number
     *  User: ETS
     *  @return string
     */
    public function routeNotificationForMTalkZ()
    {
        return $this->contact_number; // Mobile number for SMS
    }

    public function findForPassport($username)
    {
        return $this->where('contact_number', $username)->orWhere('email', $username)->first();
    }

    /**
     *  Common Display Messsage Response.
     *  User: ETS
     * @param $resource
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function GetAuthMessage($resource, $message, $authorization, $refreshToken)
    {
        $GetAuthMessage = response()->json([
            'message'       => $message,
            'authorization' => $authorization,
            'refresh_token' => $refreshToken,
            'data'          => $resource,
        ]);
        return $GetAuthMessage;
    }
}
