<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DataTrueResource;
use App\Models\Product;
use App\Http\Requests\CsvRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\ProductImageUpdateRequest;
use App\Http\Requests\ProductGalleryUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\ProductExport;
use App\Http\Requests\UpdateProductRequest;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;



/*
   |--------------------------------------------------------------------------
   | products Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the products of
     index,
     show,
     store,
     update,
     destroy,
     export and
     importBulk Methods.
   |
   */

class ProductAPIController extends Controller
{
    /**
     * list products
     * @param Request $request
     * @return ProductCollection
     */
    public function index(Request $request)
    {
        if ($request->get('is_light', false)) {

            return Cache::rememberForever('product.all', function () use ($request) {
                $product = new Product();

                $query = \App\Models\User::commonFunctionMethod(Product::select($product->light), $request, true);
                return new ProductCollection(ProductResource::collection($query), ProductResource::class);
            });
        } else
            $query = \App\Models\User::commonFunctionMethod(Product::with(['categories', 'product_galleries']), $request, true);

        return new ProductCollection(ProductResource::collection($query), ProductResource::class);
    }

    /**
     * Product Detail
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product)
    {

        return new ProductResource($product->load([]));
    }

    /**
     * Add Product
     * @param ProductRequest $request
     * @return ProductResource
     */
    public function store(ProductRequest $request)
    {

        $price = $request->unit_price * $request->set_unit;

        // $parent_id = $request->parent_id;


        $data = $request->all();


        $data['price'] = $price;


        $product = Product::create($data);
        // dd($product);

        if ($request['category_id']) {
            //this executes the insert-query
            $product->categories()->attach($request['category_id']);
        }
        if ($request['parent_id']) {
            //this executes the insert-query
            $product->subcategories()->attach($request['parent_id']);
        }


        if ($request->hasFile('featured_image')) {
            $realPath = 'product/' . $product->id;
            $resizeImages = $product->resizeImages($request->file('featured_image'), $realPath, 100, 100);

            $product->update([
                'product_id' => $product->id,
                'featured_image' => $resizeImages['image']
            ]);
        }

        if ($request->hasFile('product_galleries')) {
            $realPath = 'product/' . $product->id . '/product_galleries';
            foreach ($request->file('product_galleries') as $vImgs) {
                $resizeImages = $product->resizeImages($vImgs, $realPath, 100, 100);
                \App\Models\ProductGallery::create([
                    'product_id' => $product->id,
                    'gallery' => $resizeImages['image'],
                    'gallery_original' => $resizeImages['original'],
                    'gallery_thumbnail' => $resizeImages['thumbnail']
                ]);
            }
        }

        return \App\Models\User::GetMessage(new ProductResource($product), config('constants.messages.create_success'));
    }

    /**
     * Update Product
     * @param ProductRequest $request
     * @param Product $product
     * @return ProductResource
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        return Product::updateProduct($request, $product);
    }

    /**
     * Delete Product
     *
     * @param Request $request
     * @param Product $product
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function updateProductImage(ProductImageUpdateRequest $request, Product $product)
    {
        return Product::updateProductImage($request, $product);
    }

    /**
     * Update Product Gallery
     *
     * @param Request $request
     * @param Product $product
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */

    public function updateProductGallery(ProductGalleryUpdateRequest $request, Product $product)
    {
        return Product::updateProductGallery($request, $product);
    }

    /**
     * Delete Product
     *
     * @param Request $request
     * @param Product $product
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, Product $product)
    {
        return Product::deleteProduct($request, $product);
    }

    /**
     * Delete Product multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Product::deleteAll($request);
    }

    /**
     * Delete Product multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteProductImage(Request $request)
    {
        $urlArr = explode("/", $request->path());
        $id = end($urlArr);
        Product::where('id', $id)->update(['featured_image' => 'NULL']);

        return new DataTrueResource($request, config('constants.messages.delete_success'));
    }

    /**
     * Export Product Data
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Request $request)
    {
        $fileName = 'product_' . config('constants.file.name') . '.csv';
        $filePath = 'export/product/' . $fileName;
        $exportObj = new ProductExport($request);
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
        return \App\Models\User::importBulk($request, new ProductImport(), 'product', 'import/product/');
    }


    /**
     * Delete gallery
     * @param Request $request
     * @param \App\Models\ProductGallery $gallery
     * @return DataTrueResource
     * @throws \Exception
     */
    public function deleteGallery(Request $request, \App\Models\ProductGallery $productGallery)
    {
        $productGallery->deleteOne('product/' . $productGallery->product_id . '/product_galleries/' . basename($productGallery->gallery));
        $productGallery->deleteOne('product/' . $productGallery->product_id . '/product_galleries/thumbs/' . basename($productGallery->gallery_thumbnail));
        $productGallery->delete();

        return new DataTrueResource($productGallery, config('constants.messages.delete_success'));
    }

    /**
     *  Upload zip for import function
     *
     * \App\Http\Requests\ZipFileRequest $request
     * @return mixed
     */
    public function uploadZipFile(\App\Http\Requests\ZipFileRequest $request)
    {
        return Product::uploadZipFile($request);
    }

    /**
     *  Product display category wise
     *
     *
     * @return mixed
     */
    public function productList(Request $request)
    {
        $urlArr = explode("/", $request->path());
        $myCategoryId = end($urlArr);


        if ($request->get('is_light', false)) {
            return Cache::rememberForever('product.all', function () use ($request) {
                $product = new Product();
                $query = \App\Models\User::commonFunctionMethod(Product::select($product->light)->where('category_id', $myCategoryId), $request, true);
                return new ProductCollection(ProductResource::collection($query), ProductResource::class);
            });
        } else
            $query = \App\Models\User::commonFunctionMethod(Product::with(['categories', 'product_galleries'])->where('category_id', $myCategoryId), $request, true);

        return new ProductCollection(ProductResource::collection($query), ProductResource::class);
    }

    /**
     * Update Product status
     * @param Request $request
     * @return ProductResource
     */
    public function updatestatus(UpdateProductRequest $request, Product $product)
    {
        $product = Product::find($product->id);
        $product->status = $request->status;
        $product->save();

        return \App\Models\User::GetMessage(new ProductResource($product), config('constants.messages.update_success'));
    }
}
