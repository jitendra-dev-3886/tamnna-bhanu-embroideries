<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\CheckEmailExistsRequest;
use App\Http\Requests\CsvRequest;
use App\Exports\UserExport;
use App\Exports\CustomerExport;
use App\Imports\UserImport;
use App\Models\User;
use App\Http\Resources\ActivityResource;
use App\Models\ActivityLog;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use URL;
use Illuminate\Support\Facades\Cache;

/*
 |--------------------------------------------------------------------------
 | Users Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles the users of
     index,
     show,
     store,
     update,
     destroy,
     export,
     importBulk,
     batchRequest,
     getActivities,
     checkEmail Methods.
 |
 */

class UserAPIController extends Controller
{

    /**
     * list users
     * @param Request $request
     * @return UserCollection
     */
    public function index(Request $request)
    {
        if ($request->get('is_light', false)) {

            return Cache::rememberForever('user.all', function () use ($request) {
                $user = new User();
                $query = User::commonFunctionMethod(User::select($user->light), $request, true);
                return new UserCollection(UserResource::collection($query), UserResource::class);
            });
        } else {
            $user_role_id = config('constants.user.user_type_code.customer');
            $query = User::commonFunctionMethod(User::where('role_id', '!=', $user_role_id)->with(['role']), $request, true);
        }

        return new UserCollection(UserResource::collection($query), UserResource::class);
    }

    /**
     * User Detail
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user->load([]));
    }

    /**
     * Add User
     * @param UserRequest $request
     * @return mixed
     */
    public function register(UserRequest $request)
    {
        $user = User::create($request->all());
        return \App\Models\User::GetMessage(new UserResource($user), config('constants.messages.success_app_registration'));
    }

    /**
     * Add User
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        return User::createUser($request);
    }

    /**
     * Update User
     * @param UserUpdateRequest $request
     * @param User $user
     * @return mixed
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        return User::updateUser($request, $user);
    }

    /**
     * Delete User
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    public function destroy(Request $request, User $user)
    {
        return User::deleteUser($request, $user);
    }

    /**
     * Delete User multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return User::deleteAll($request);
    }

    /**
     * Export User Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        $fileName = 'user_' . config('constants.file.name') . '.csv';
        $filePath = 'export/user/' . $fileName;
        $exportObj = new UserExport($request);
        Excel::store($exportObj, $filePath);

        return response()->download(storage_path("app/public/{$filePath}"));
    }

    /**
     * Export User Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customersExport(Request $request)
    {
        $fileName = 'user_' . config('constants.file.name') . '.csv';
        $filePath = 'export/user/' . $fileName;
        $exportObj = new CustomerExport($request);
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
        return User::importBulk($request, new UserImport(), 'user', 'import/user/');
    }

    /**
     * This is a batch request API
     *
     * @param Request $requestObj
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchRequest(Request $requestObj)
    {
        $requests  = $requestObj->get('request'); //get request
        $output = array();
        $cnt = 0;
        foreach ($requests as $request) { // foreach for all requests inside batch

            $request = (object) $request; // array request convert to object

            if ($cnt == 10) // limit maximum call 10 requests
                break;

            $url = parse_url($request->url);

            //querystrings code
            $query = array();
            if (isset($url['query'])) {
                parse_str($url['query'], $query);
            }

            $server = ['HTTP_HOST' => preg_replace('#^https?://#', '', URL::to('/')), 'HTTPS' => 'on'];
            $req = Request::create($request->url, 'GET', $query, [], [], $server); // set request

            $req->headers->set('Accept', 'application/json'); //set accept header
            $res = app()->handle($req); //call request

            if (isset($request->request_id)) { // check request_id is set or not
                $output[$request->request_id] = json_decode($res->getContent()); // get response and set into output array
            } else {
                $output[] = $res;
            }

            $cnt++; // request counter
        }

        return response()->json(array('response' => $output)); // return batch response
    }

    /**
     *  This get activities API
     *
     * @param Request $request
     * @return mixed
     */
    public function getActivities(Request $request)
    {
        if ($request->get('is_light', false)) {
            $activityLog = new ActivityLog();
            $query = User::commonFunctionMethod(ActivityLog::select($activityLog->light), $request, true);
        } else
            $query = User::commonFunctionMethod(ActivityLog::with(['causer']), $request, true);

        return new UserCollection(ActivityResource::collection($query), ActivityResource::class);
    }

    /**
     * check if email is exists or not.
     *
     * @param CheckEmailExistsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail(CheckEmailExistsRequest $request)
    {
        return User::GetMessage(User::fieldExist($request, 'email'), "");
    }

    public function customers(Request $request)
    {
        $user_role_id = config('constants.user.user_type_code.customer');
        $query = User::commonFunctionMethod(User::where('role_id', $user_role_id), $request, true);
        return new UserCollection(UserResource::collection($query), UserResource::class);
    }
}
