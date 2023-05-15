<?php

namespace App\Models;

use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\RoleResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Role extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = ['id', 'name', 'guard_name', 'landing_page'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = ['id', 'name', 'guard_name', 'landing_page'];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = [];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = ['id', 'name'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = ['users'];

    /**
     * @var array
     */
    public $sortable = ['roles.created_at', 'roles.id', 'name', 'guard_name', 'landing_page'];

    /**
     * @var array
     */
    public $foreign_sortable = [];

    /**
     * @var array
     */
    public $foreign_table = [];

    /**
     * @var array
     */
    public $foreign_key = [];

    /**
     * @var array
     */
    public $foreign_method = [];

    /**
     * @var array
     */
    public $type_sortable = [];

    /**
     * @var array
     */
    public $type_enum = [];

    /**
     * @var array
     */
    public $type_enum_text = [];

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
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

        'id' => 'string',
        'name' => 'string',
        'landing_page' => 'string',
        'guard_name' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'

    ];





    /**
     * Add Role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateRole($query, $request)
    {
        $role = Role::create($request->all());



        return \App\Models\User::GetMessage(new RoleResource($role), config('constants.messages.create_success'));
    }

    /**
     * Update Role
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateRole($query, $request, $role)
    {
        $data = $request->all();


        $role->update($data);

        return \App\Models\User::GetMessage(new RoleResource($role), config('constants.messages.update_success'));
    }

    /**
     * Delete Role
     *
     * @param Request $request
     * @param Role $role
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteRole($query, $request, $role)
    {
        if ($role->id == config('constants.system_role_id'))
            return User::GetError(config('constants.messages.admin_role_delete_error'));

        $inUse = Role::commonCodeForDeleteModelRestrictions([User::class], 'role_id', $role->id);

        if (!empty($inUse)) {
            $role = Role::where('id', $role->id)->first();
            return User::GetError($role->name . " can't be deleted as the [" . implode(", ", $inUse) . "] are associated with it. Please remove the role mapping with [" . implode(",", $inUse) . "] in order to delete the role.");
        }

        $role->delete();

        return new DataTrueResource($role, config('constants.messages.delete_success'));
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

            if (in_array(config('constants.system_role_id'), $request->id)) {
                return User::GetError(config('constants.messages.admin_role_delete_error'));
            }

            $roleName = "";
            $inUses = [];
            foreach ($request->id as $roleId) {
                $inUse = Role::commonCodeForDeleteModelRestrictions([User::class], 'role_id', $roleId);
                $role = Role::where('id', $roleId)->first();
                if (!empty($inUse))
                    $roleName .= $role->name . ", ";
                $inUses[] = implode(", ", $inUse);
            }

            $roleTrim = trim($roleName, ",");
            $arr = array_diff(array_unique($inUses), array(""));
            if ($roleTrim != "") {
                return User::GetError($roleTrim . " can't be deleted as the [" . implode(",", $arr) . "] are associated with it. Please remove the role mapping with [" . implode(", ", $arr) . "] in order to delete the role.");
            } else {
                Role::whereIn('id', $request->id)->delete();
                return new DataTrueResource(true, config('constants.messages.delete_success'));
            }
        } else {
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }


    /**
     * Get the Permissions for the Role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, "permission_role", "role_id", "permission_id");
    }
}
