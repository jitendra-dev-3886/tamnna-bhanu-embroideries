<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DataTrueResource;
use App\Models\Category;
use App\Http\Requests\CsvRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\SubcategoryRequest;
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
use App\Http\Controllers\API\JsonResponse;


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
        // return Category::createCategory($request);
        $request['parent_id'] = (int)$request->parent_id;

        $Category = new Category();
        $Category->name       = $request->name;
        $Category->description = $request->description;
        $Category->parent_id = $request->parent_id;
        $Category->category_status = 1;
        $Category->save();
        // $category = Category::create($request->all());

        if( $Category->id == $request['parent_id'] ) {
            $Category->update([
                'parent_id'    => 0,
            ]);
        } else {
            $Category->update([
                'parent_id'    => $request['parent_id'],
            ]);
        }

        if ($request->hasFile('featured_image')) {
            $realPath = 'category/' . $Category->id;
            $resizeImages = $Category->resizeImages($request->file('featured_image'), $realPath, 100, 100);

            $Category->update([
                'category_id'    => $Category->id,
                'featured_image' => $resizeImages['image']
                // 'profile_original' => $request->get('original'),
                // 'profile_thumbnail' => $resizeImages['thumbnail']
            ]);
        }

        return \App\Models\User::GetMessage(new CategoryResource($Category), config('constants.messages.create_success'));
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
     * Delete Category Image
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteCategoryImage(Request $request)
    {
        $urlArr = explode("category/", $request->path());
        $id = end($urlArr);

         Category::where('id', $id)->update(['featured_image'=>'NULL']);

         return new DataTrueResource($request, config('constants.messages.delete_success'));
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
     * Sub category
     * @param Request $request
     * @return JsonResponse
     */
    public function subcategories(SubcategoryRequest $request) {

        if (!empty($request->id)) {
            $SubCategoryIds = Category::wherein('parent_id', $request->id)->get();
            // dd($SubCategoryIds);
            if( !empty( $SubCategoryIds ) ) {
                return response()->json(
                    [
                        // 'message'  => "Sub Categories Founds Me!",
                        'data' => $SubCategoryIds,
                    ],
                    config('constants.validation_codes.ok')
                );
            }
        }

    }

    public function parentCategories() {
        return response()->json(['data' => Category::select(['id', 'name', 'parent_id', 'description', 'featured_image'])->where(['parent_id' => 0])->get()]);
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

      /**
     * Categories List of Display Active categories
     * @param Request $request
     * @return CategoryCollection
     */
    public function catagoriesList(Request $request)
    {
        if($request->get('is_light',false)){
            return Cache::rememberForever('category.all', function () use($request){
                $category = new Category();
                $query = \App\Models\User::commonFunctionMethod(Category::select($category->light)->where('category_status', config('constants.category.category_status_enum.active'))->where( 'parent_id', "0" ),$request,true);
                return new CategoryCollection(CategoryResource::collection($query),CategoryResource::class);
            });
        } else {
            $query = \App\Models\User::commonFunctionMethod(Category::where( 'category_status', config('constants.category.category_status_enum.active') )->where( 'parent_id', "0" ) ,$request, true);
        }

        return new CategoryCollection(CategoryResource::collection($query),CategoryResource::class);
    }

}
