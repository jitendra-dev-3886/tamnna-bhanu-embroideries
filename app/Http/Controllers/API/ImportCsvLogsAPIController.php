<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\ImportCsvLog;
use App\Http\Resources\ImportCsvLogsCollection;
use App\Http\Resources\ImportCsvLogsResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

/*
   |--------------------------------------------------------------------------
   | ImportCsvLogs Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the Roles of
       index,
       show,
   |
   */
class ImportCsvLogsAPIController extends Controller
{
    /**
     * List All
     * @param Request $request
     * @return ImportCsvLogsCollection
     */
    public function index(Request $request)
    {
        $query = User::commonFunctionMethod(ImportCsvLog::class,$request);
        return new ImportCsvLogsCollection(ImportCsvLogsResource::collection($query),ImportCsvLogsResource::class);
    }

    /**
     * import_csv_log detail
     * @param ImportCsvLog $import_csv_log
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ImportCsvLog $importCsvLog)
    {        
        if(is_null($importCsvLog->error_log))
            return User::GetError(config('constants.messages.error_log_not_available'));

        return response()->json(['message' => "",'data' => \GuzzleHttp\json_decode($importCsvLog->error_log)], config('constants.validation_codes.ok'));
    }

}
