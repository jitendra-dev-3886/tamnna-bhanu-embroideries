<?php

namespace App\Models;
use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\PermissionResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Permission extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = [ 'id', 'name', 'label', 'guard_name', 'created_by', 'updated_by', 'deleted_by' ];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = [ 'id', 'name', 'label', 'guard_name' ];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = [  ];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = [ 'id', 'name' ];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = [  ];

    /**
     * @var array
     */
    public $sortable = [ 'permissions.created_at', 'permissions.id', 'name', 'label', 'guard_name' ];

    /**
     * @var array
     */
    public $foreign_sortable = [  ];

    /**
     * @var array
     */
    public $foreign_table = [  ];

    /**
     * @var array
     */
    public $foreign_key = [  ];

    /**
     * @var array
     */
    public $foreign_method = [  ];

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
    protected $hidden = [  ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

            'id'=>'string', 
            'name'=>'string', 
            'label'=>'string', 
            'guard_name'=>'string', 
            'created_by'=>'string', 
            'updated_by'=>'string', 
            'deleted_by'=>'string'

    ];

    

    

    /**
     * Add Permission
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreatePermission($query, $request){
        $permission = Permission::create($request->all());
        
        
        
        return \App\Models\User::GetMessage(new PermissionResource($permission), config('constants.messages.create_success'));
    }

    /**
     * Update Permission
     * @param Request $request
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdatePermission($query, $request, $permission){
        $data = $request->all();
        
        
        $permission->update($data);
        
        return \App\Models\User::GetMessage(new PermissionResource($permission), config('constants.messages.update_success'));
    }

    /**
     * Delete Permission
     *
     * @param Request $request
     * @param Permission $permission
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeletePermission($query, $request, $permission){
       
       
       $permission->delete();
       
       return new DataTrueResource($permission,config('constants.messages.delete_success'));
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query,$request){
        if(!empty($request->id)) {

            Permission::whereIn('id', $request->id)->get()->each(function($permission) {
                
                
                $permission->delete();
            });

            

            return new DataTrueResource(true,config('constants.messages.delete_success'));
        }
        else{
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }

    
    /**
     * @param $role
     * @return array - array of permission
     */
    public static function getPermissions($role, $permission = null)
    {
        $isPermission = $role->permissions->pluck("id")->toArray();// get all role permissions mappings
        $allPermission = [];

        $rootPermissions = Self::getPermissionByGuardName("root");// get permissions

        if (!$rootPermissions->isEmpty()) {// Check permissions is not empty
            foreach ($rootPermissions As $root) {

                $continue = true;// set flag for specific model response

                if($permission){// Check permission is not null

                    if($root["name"] != $permission->guard_name)
                        $continue = false;

                }

                if($continue){

                    $root = Self::commonPermissionCode($root, $isPermission);
                    $firstPermission = [];
                    $firstPermissions = Self::getPermissionByGuardName($root["name"]);// get permissions

                    if (!$firstPermissions->isEmpty()) {// Check permissions is not empty
                        foreach ($firstPermissions As $first) {

                            $first = Self::commonPermissionCode($first, $isPermission);
                            $name = explode("-",$first["name"]);
                            $first["name"] = $name[0];
                            $firstPermission[] = $first;
                        }
                    }

                    $root["sub_permissions"] = $firstPermission;
                    $allPermission[] = $root;

                }
            }
        }

        return $allPermission;
    }

    
            
    /**
     * This method is used for display name for permission and it's status
     *
     * @param $array
     * @param $isPermission
     * @return mixed
     */
    public static function commonPermissionCode($array,$isPermission)
    {
        $array["is_permission"] = config("constants.permission.has_not_permission");
        if(in_array($array["id"],$isPermission))
                $array["is_permission"] = config("constants.permission.has_permission");


        $name = str_replace("-", " ",$array["name"]);
        $name = str_replace("and", "&",$name);
        $name = str_replace("slash", "/",$name);
        $array["display_name"] = ucwords($name);
        return $array;
    }

    
            
    /**
     * This static method is used to get permissions by guardname
     *
     * @param $guardName
     * @return mixed
     */
    public static function getPermissionByGuardName($guardName)
    {
        return Permission::select("id","name","label","guard_name")
            ->where("guard_name",$guardName)
            ->orderBy("created_at","asc")
            ->get();
    }

    
            
    /**
     * This static method is used to set and unset permission to role
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function setUnsetPermission($request)
    {
        $permissionRole = PermissionRole::where("role_id",$request->get("role_id"))
            ->where("permission_id",$request->get("permission_id"))->first();

        if($request->get("is_permission") == "1"){

            if(is_null($permissionRole)){

                PermissionRole::insert([
                    "role_id" => $request->get("role_id"),
                    "permission_id" => $request->get("permission_id"),
                ]);

            }

        }else{
            PermissionRole::where("role_id",$request->get("role_id"))
                ->where("permission_id",$request->get("permission_id"))->delete();

        }

        return response()->json(["message" => config('constants.messages.change_success'),"data" => true]);
    }

    
}
