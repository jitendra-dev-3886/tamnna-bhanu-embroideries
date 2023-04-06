<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DataTrueResource;
use App\Models\Permission;
use App\Http\Requests\CsvRequest;
use App\Http\Requests\PermissionRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Http\Resources\PermissionCollection;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\PermissionExport;
use App\Imports\PermissionImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;


use App\Http\Requests\SetUnsetPermissionToRoleRequest;
            

/*
   |--------------------------------------------------------------------------
   | permissions Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the permissions of
     index,
     show,
     store,
     update,
     destroy,
     export and
     importBulk Methods.
   |
   */

class PermissionAPIController extends Controller
{
    /**
     * list permissions
     * @param Request $request
     * @return PermissionCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){

            return Cache::rememberForever('permission.all', function () use($request){
                $permission = new Permission();
                $query = \App\Models\User::commonFunctionMethod(Permission::select($permission->light),$request,true);
                return new PermissionCollection(PermissionResource::collection($query),PermissionResource::class);
            });
        }
        else
            $query = \App\Models\User::commonFunctionMethod(Permission::class,$request);

        return new PermissionCollection(PermissionResource::collection($query),PermissionResource::class);
    }

    /**
     * Permission Detail
     * @param Permission $permission
     * @return PermissionResource
     */
    public function show(Permission $permission)
    {
        return new PermissionResource($permission->load([]));
    }

    /**
     * Add Permission
     * @param PermissionRequest $request
     * @return PermissionResource
     */
    public function store(PermissionRequest $request)
    {
        return Permission::createPermission($request);
    }

    /**
     * Update Permission
     * @param PermissionRequest $request
     * @param Permission $permission
     * @return PermissionResource
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        return Permission::updatePermission($request, $permission);
    }

    /**
     * Delete Permission
     *
     * @param Request $request
     * @param Permission $permission
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Permission $permission)
    {
        return Permission::deletePermission($request, $permission);
    }

    /**
     * Delete Permission multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Permission::deleteAll($request);
    }

    /**
     * Export Permission Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function export(Request $request)
     {
        $fileName = 'permission_'.config('constants.file.name').'.csv';
        $filePath = 'export/permission/'.$fileName;
        $exportObj = new PermissionExport($request);
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
         return \App\Models\User::importBulk($request,new PermissionImport(),'permission','import/permission/');
      }

      
    /**
     * This method is used set/unset permission to role
     *
     * @param SetUnsetPermissionToRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUnsetPermissionToRole(SetUnsetPermissionToRoleRequest $request)
    {
        return Permission::setUnsetPermission($request);
    }

    
}
