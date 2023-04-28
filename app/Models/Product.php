<?php

namespace App\Models;

use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Category;

class Product extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = ['id', 'name', 'price', 'description', 'item_code', 'available_status', 'stock', 'featured_image'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = ['id', 'name', 'price', 'description', 'item_code', 'category->name', 'available_status', 'stock', 'featured_image', 'product_galleries->gallery'];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = ['categories', 'product_galleries'];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = ['id', 'name', 'price', 'description', 'item_code', 'featured_image'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = ['carts'];

    /**
     * @var array
     */
    public $sortable = ['products.created_at', 'products.id', 'name', 'price', 'item_code', 'stock', 'featured_image'];

    /**
     * @var array
     */
    public $foreign_sortable = ['category_id'];

    /**
     * @var array
     */
    public $foreign_table = ['categories'];

    /**
     * @var array
     */
    public $foreign_key = ['name'];

    /**
     * @var array
     */
    public $foreign_method = ['categories'];

    /**
     * @var array
     */
    public $type_sortable = ['available_status'];

    /**
     * @var array
     */
    public $type_enum = [
        ['constants.product.available_status_enum.not-available', 'constants.product.available_status_enum.available']
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
        ['constants.product.available_status.0', 'constants.product.available_status.1']
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
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

        'id' => 'string',
        'name' => 'string',
        'price' => 'string',
        'description' => 'string',
        'item_code' => 'string',
        'available_status' => 'string',
        'featured_image' => 'string'

    ];

    /**
     * @param $value
     * @return mixed
     */
    public function getFeaturedImageAttribute($value)
    {

        if ($this->is_file_exists($value))
            return \Illuminate\Support\Facades\Storage::url($value);
        else
            return asset(config('constants.image.default_img'));
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function category()
    // {
    //     return $this->belongsTo(\App\Models\Category::class);
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product_galleries()
    {
        return $this->hasMany(\App\Models\ProductGallery::class, 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {

        return $this->belongsToMany(Category::class, "category_products", "product_id", "category_id");
    }

    /**
     * Add Product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateProduct($query, $request)
    {
        $data = $request->all();

        $product = Product::create($data);

        if ($request['category_id']) {
            //this executes the insert-query
            $product->categories()->attach($request['category_id']);
        }


        if ($request->hasFile('featured_image')) {
            $realPath = 'product/' . $product->id;
            $resizeImages = $product->resizeImages($request->file('featured_image'), $realPath, 100, 100);

            $product->update([
                'product_id' => $product->id,
                'featured_image' => $resizeImages['image']
                // 'profile_original' => $request->get('original'),
                // 'profile_thumbnail' => $resizeImages['thumbnail']
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
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateProduct($query, $request, $product)
    {
        $data = $request->all();

        if ($request->hasFile('featured_image')) {
            $realPath = 'product/' . $product->id;
            $resizeImages = $product->resizeImages($request->file('featured_image'), $realPath, 100, 100);

            $data['featured_image'] = $resizeImages['image'];
            // $data['profile_original'] = $request->get('profile_image_original');
            // $data['profile_thumbnail'] = $resizeImages['thumbnail'];
        }

        if ($request->hasFile('product_galleries')) {
            \App\Models\ProductGallery::where(['product_id' => $product->id])->delete();

            $realPath = 'product/' . $product->id . '/product_galleries';
            \Illuminate\Support\Facades\Storage::deleteDirectory('/public/' . $realPath);

            foreach ($request->file('product_galleries') as $v_imgs) {
                $resizeImages = $product->resizeImages($v_imgs, $realPath, 100, 100);
                \App\Models\ProductGallery::create([
                    'product_id' => $product->id,
                    'gallery' => $resizeImages['image'],
                    'gallery_original' => $resizeImages['original'],
                    'gallery_thumbnail' => $resizeImages['thumbnail']
                ]);
            }
        }

        $product->update($data);

        return \App\Models\User::GetMessage(new ProductResource($product), config('constants.messages.update_success'));
    }

    /**
     * Delete Product
     *
     * @param Request $request
     * @param Product $product
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteProduct($query, $request, $product)
    {


        $this->singleImageDelete($product, "product/"); // Delete image

        $product->delete();

        return new DataTrueResource($product, config('constants.messages.delete_success'));
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

            Product::whereIn('id', $request->id)->get()->each(function ($product) {


                $this->singleImageDelete($product, "product/"); // Delete image

                $product->delete();
            });



            return new DataTrueResource(true, config('constants.messages.delete_success'));
        } else {
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }
}
