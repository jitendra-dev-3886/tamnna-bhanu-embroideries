<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\DataTrueResource;
use App\Models\HomeBanner;
use App\Http\Requests\CsvRequest;
use App\Http\Requests\HomeBannerRequest;
use App\Http\Requests\HomeBannerImageUpdateRequest;
use App\Http\Requests\HomeBannerUpdateRequest;
use App\Http\Resources\HomeBannerCollection;
use App\Http\Resources\HomeBannerResource;
use App\Exports\HomeBannerExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;



/*
   |--------------------------------------------------------------------------
   | HomeBanners Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the HomeBanners of
     index,
     show,
     store,
     update,
     destroy,
     export and
     importBulk Methods.
   |
   */

class HomeBannerAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('is_light', false)) {

            return Cache::rememberForever('homebanner.all', function () use ($request) {
                $homebanner = new HomeBanner();
                $query = \App\Models\User::commonFunctionMethod(HomeBanner::select($homebanner->light), $request, true);
                return new HomeBannerCollection(HomeBannerResource::collection($query), HomeBannerResource::class);
            });
        } else
            $query = \App\Models\User::commonFunctionMethod(HomeBanner::class, $request);

        return new HomeBannerCollection(HomeBannerResource::collection($query), HomeBannerResource::class);
    }

    /**
     * HomeBanner Detail
     * @param HomeBanner $homebanner
     * @return HomeBannerResource
     */
    public function show(HomeBanner $homebanner)
    {
        return new HomeBannerResource($homebanner->load([]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HomeBannerRequest $request)
    {
        return HomeBanner::createHomeBanner($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HomeBannerUpdateRequest $request, HomeBanner $homebanner)
    {
        return HomeBanner::updateHomeBanner($request, $homebanner);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateBannerImage(HomeBannerImageUpdateRequest $request, HomeBanner $homebanner)
    {
        return HomeBanner::updateHomeBannerImage($request, $homebanner);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, HomeBanner $homebanner)
    {
        return HomeBanner::deleteHomeBanner($request, $homebanner);
    }

    /**
     * Delete HomeBanner multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return HomeBanner::deleteAll($request);
    }

    /**
     * Delete HomeBanner Image
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteBannerImage(Request $request)
    {
        $urlArr = explode("/", $request->path());
        $id = end($urlArr);
        HomeBanner::where('id', $id)->update(['featured_image'=>'NULL']);

            // return  "Home Banner image deleted successfully!!!";
            return new DataTrueResource($request, config('constants.messages.delete_success'));
    }


    /**
     * Export HomeBanner Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        $fileName = 'homebanner_' . config('constants.file.name') . '.csv';
        $filePath = 'export/homebanner/' . $fileName;
        $exportObj = new HomeBannerExport($request);
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
        // return \App\Models\User::importBulk($request, new HomeBannerImport(), 'homebanner', 'import/homebanner/');
    }

    /**
     *  Upload zip for import function
     *
     * \App\Http\Requests\ZipFileRequest $request
     * @return mixed
     */
    // public function uploadZipFile(\App\Http\Requests\ZipFileRequest $request)
    // {
    //     return HomeBanner::uploadZipFile($request);
    // }
}
