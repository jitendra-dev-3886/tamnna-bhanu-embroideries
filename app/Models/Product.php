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
    protected $fillable = ['id', 'name', 'price', 'description', 'item_code', 'available_status', 'stock', 'featured_image', 'available_color', 'set_unit', 'unit_price'];

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
    public $light = ['id', 'name', 'price', 'description', 'item_code', 'featured_image', 'available_color', 'set_unit', 'unit_price'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = ['carts'];

    /**
     * @var array
     */
    public $sortable = ['products.created_at', 'products.id', 'name', 'price', 'item_code', 'stock', 'featured_image', 'description', 'available_color', 'set_unit', 'unit_price', 'status'];

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
    public $type_sortable = ['available_status', 'status'];

    /**
     * @var array
     */
    public $type_enum = [
        ['constants.products.available_status_code.not-available', 'constants.products.available_status_code.available', 'constants.products.status_enum.inactive', 'constants.products.status_enum.active']
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
        ['constants.products.available_status.0', 'constants.products.available_status.1', 'constants.products.status.0', 'constants.products.status.1']
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
        'status' => 'string',
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

        return $this->belongsToMany(Category::class, "category_products", "product_id", "category_id")->with(['subcategories']);
    }
    public function subcategories()
    {

        return $this->belongsToMany(Category::class, "category_products", "product_id",  "parent_id")->with(['subCategories']);
    }




    /**
     * Update Product
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateProduct($query, $request, $product)
    {


        $price = $request->unit_price * $request->set_unit;

        //this executes the  removal of categories with product
        $product->subcategories()->detach();

        if ($request['category_id']) {
            //this executes the insert-query
            $product->categories()->attach($request['category_id']);
        }

        if ($request['parent_id']) {
            //this executes the insert-query
            $product->subcategories()->attach($request['parent_id']);
        }

        //$parent_id = $request->parent_id;

        // $data['sub_category_id'] = $parent_id;

        $data = $request->all();
        $data['price'] = $price;

        $product->update($data);

        return \App\Models\User::GetMessage(new ProductResource($product), config('constants.messages.update_success'));
    }

    /**
     * Update Product
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateProductImage($query, $request, $product)
    {
        if ($request->hasFile('featured_image')) {
            $realPath = 'product/' . $product->id;
            $resizeImages = $product->resizeImages($request->file('featured_image'), $realPath, 100, 100);

            $product->update([
                'featured_image' => $resizeImages['image']
            ]);
        }
        return \App\Models\User::GetMessage(new ProductResource($product), config('constants.messages.update_success'));
    }

    /**
     * Update Product
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateProductGallery($query, $request, $product)
    {
        $data = $request->all();

        if ($request->hasFile('product_galleries')) {

            $realPath = 'product/' . $product->id . '/product_galleries';

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
