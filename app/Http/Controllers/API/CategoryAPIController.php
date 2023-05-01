<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DataTrueResource;
use App\Models\Category;
use App\Http\Requests\CsvRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\CategoryImageUpdateRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;



/*
   |--------------------------------------------------------------------------
   | categories Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the categories of
     index,
     show,
     store,
     update,
     destroy,
     export and
     importBulk Methods.
   |
   */

class CategoryAPIController extends Controller
{
    /**
     * list categories
     * @param Request $request
     * @return CategoryCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)){

            return Cache::rememberForever('category.all', function () use($request){
                $category = new Category();
                $query = \App\Models\User::commonFunctionMethod(Category::select($category->light),$request,true);
                return new CategoryCollection(CategoryResource::collection($query),CategoryResource::class);
            });
        }
        else
            $query = \App\Models\User::commonFunctionMethod(Category::class,$request);

        return new CategoryCollection(CategoryResource::collection($query),CategoryResource::class);
    }

    /**
     * Category Detail
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category)
    {
        return new CategoryResource($category->load([]));
    }

    /**
     * Add Category
     * @param CategoryRequest $request
     * @return CategoryResource
     */
    public function store(CategoryRequest $request)
    {
        return Category::createCategory($request);
    }

    /**
     * Update Category
     * @param CategoryRequest $request
     * @param Category $category
     * @return CategoryResource
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        // dd($request);
        return Category::updateCategory($request, $category);
    }

      /**
     * Update Category
     * @param CategoryRequest $request
     * @param Category $category
     * @return CategoryResource
     */
    public function updateCategoryImage(CategoryImageUpdateRequest $request, Category $category)
    {
        // dd($request);
        return Category::updateCategory($request, $category);
    }

    /**
     * Delete Category
     *
     * @param Request $request
     * @param Category $category
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Category $category)
    {
        return Category::deleteCategory($request, $category);
    }

    /**
     * Delete Category multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Category::deleteAll($request);
    }

    /**
     * Export Category Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function export(Request $request)
     {
        $fileName = 'category_'.config('constants.file.name').'.csv';
        $filePath = 'export/category/'.$fileName;
        $exportObj = new CategoryExport($request);
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
         return \App\Models\User::importBulk($request,new CategoryImport(),'category','import/category/');
      }


}
