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

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, Scopes, SoftDeletes, HasFactory, UploadTrait;

   /**
    * @var array
    */
   protected $fillable = [ 'id', 'email', 'password', 'role_id', 'email_verified_at', 'remember_token', 'last_login_time', 'last_seen_at'];

   /**
    * Activity log array
    *
    * @var array
    */
   public $activity_log = [ 'id', 'email', 'role->name', 'last_login_time', 'last_seen_at' ];

   /**
    * Log Activity relationships array
    *
    * @var array
    */
   public $log_relations = [ 'role' ];

   /**
    * Lightweight response variable
    *
    * @var array
    */
   public $light = [ 'id', 'email' ];

   /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = [ 'orders', 'carts' ];

   /**
    * @var array
    */
   public $sortable = [ 'users.created_at', 'users.id', 'email' ];

   /**
    * @var array
    */
   public $foreign_sortable = [ 'role_id' ];

   /**
    * @var array
    */
   public $foreign_table = [ 'roles' ];

   /**
    * @var array
    */
   public $foreign_key = [ 'name' ];

   /**
    * @var array
    */
   public $foreign_method = [ 'role' ];

   /**
    * @var array
    */
   public $type_sortable = [  ];

   /**
    * @var array
    */
   public $type_enum = [
            
   ];

   /**
    * @var array
    */
   public $type_enum_text = [
            
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
   protected $hidden = [ 'password', 'remember_token' ];

   /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
   protected $casts = [

       'id'=>'string', 
            'email'=>'string', 
            'role_id'=>'string', 
            'email_verified_at'=>'string', 
            'remember_token'=>'string'

   ];

    
   
    /**
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function role() {
       return $this->belongsTo(\App\Models\Role::class);
    }
        


    public function scopeCommonFunctionMethod($query, $model, $request, $preQuery = null, $tablename = null, $groupBy = null, $export_select = false, $no_paginate = false)
    {
        return $this->getCommonFunctionMethod($model, $request, $preQuery, $tablename , $groupBy , $export_select , $no_paginate);
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
    public static function GetError($message){
        return response()->json(['message' => $message,'errors' => (object)[]], config('constants.validation_codes.unassigned'));
    }

     /**
     *  Common Display Messsage Response.
     *
     * @param $resource
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function GetMessage($resource, $message){

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
            'last_login_time' => time(),
            'last_seen_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Add User
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateUser($query, $request){
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        
        
        
        return \App\Models\User::GetMessage(new UserResource($user), config('constants.messages.create_success'));
    }

    /**
     * Update User
     * @param $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateUser($query, $request, $user){
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
    public function scopeDeleteUser($query, $request, $user){

        if ($user->id == config('constants.system_user_id'))
            return User::GetError(config('constants.messages.admin_user_delete_error'));

        
        
        $user->delete();
        
        return new DataTrueResource($user,config('constants.messages.delete_success'));
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query,$request){
        if(!empty($request->id)) {

            if (in_array(config('constants.system_user_id'), $request->id))
                return User::GetError(config('constants.messages.admin_user_delete_error'));

            User::whereIn('id', $request->id)->get()->each(function($user) {
                    
                
                $user->delete();
            });

            

            return new DataTrueResource(true,config('constants.messages.delete_success'));
        }
        else{
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }

    
}
