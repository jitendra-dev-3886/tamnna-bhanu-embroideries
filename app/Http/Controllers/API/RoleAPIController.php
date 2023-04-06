<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DataTrueResource;
use App\Models\Role;
use App\Http\Requests\CsvRequest;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\RoleExport;
use App\Imports\RoleImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;



/*
   |--------------------------------------------------------------------------
   | roles Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the roles of
     index,
     show,
     store,
     update,
     destroy,
     export and
     importBulk Methods.
   |
   */

class RoleAPIController extends Controller
{
    /**
     * list roles
     * @param Request $request
     * @return RoleCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){

            return Cache::rememberForever('role.all', function () use($request){
                $role = new Role();
                $query = \App\Models\User::commonFunctionMethod(Role::select($role->light),$request,true);
                return new RoleCollection(RoleResource::collection($query),RoleResource::class);
            });
        }
        else
            $query = \App\Models\User::commonFunctionMethod(Role::class,$request);

        return new RoleCollection(RoleResource::collection($query),RoleResource::class);
    }

    /**
     * Role Detail
     * @param Role $role
     * @return RoleResource
     */
    public function show(Role $role)
    {
        return new RoleResource($role->load([]));
    }

    /**
     * Add Role
     * @param RoleRequest $request
     * @return RoleResource
     */
    public function store(RoleRequest $request)
    {
        return Role::createRole($request);
    }

    /**
     * Update Role
     * @param RoleRequest $request
     * @param Role $role
     * @return RoleResource
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        return Role::updateRole($request, $role);
    }

    /**
     * Delete Role
     *
     * @param Request $request
     * @param Role $role
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Role $role)
    {
        return Role::deleteRole($request, $role);
    }

    /**
     * Delete Role multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Role::deleteAll($request);
    }

    /**
     * Export Role Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function export(Request $request)
     {
        $fileName = 'role_'.config('constants.file.name').'.csv';
        $filePath = 'export/role/'.$fileName;
        $exportObj = new RoleExport($request);
        Excel::store($exportObj, $filePath);

        return response()->download(storage_path("app/public/{$filePath}"));
     }

      /**
      * Import bulk
      * @param CsvRequest $request
      * @return \Illuminate\Http\JsonResponse
      */
      public function importBulk(CsvRequest $request)
      {
         return \App\Models\User::importBulk($request,new RoleImport(),'role','import/role/');
      }

      
    /**
     * Role Detail
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function getPermissionsByRole($id)
    {
        $role = Role::where("id",$id)->with("permissions")->firstorfail();
        $allPermission = \App\Models\Permission::getPermissions($role);
        return response()->json(["message" => "", "data" => $allPermission]);
    }

    
}
