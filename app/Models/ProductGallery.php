<?php

namespace App\Models;

use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\ProductGalleryResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class ProductGallery extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = ['id', 'product_id', 'gallery', 'gallery_original', 'gallery_thumbnail'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = [];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = [];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = ['id', 'product_id', 'gallery'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = ['products'];

    /**
     * @var array
     */
    public $sortable = ['product_galleries.created_at', 'product_galleries.id',];

    /**
     * @var array
     */
    public $foreign_sortable = ['product_id'];

    /**
     * @var array
     */
    public $foreign_table = ['products'];

    /**
     * @var array
     */
    public $foreign_key = ['name'];

    /**
     * @var array
     */
    public $foreign_method = ['product'];

    /**
     * @var array
     */
    public $type_sortable = [];

    /**
     * @var array
     */
    public $type_enum = [];

    /**
     * @var array
     */
    public $type_enum_text = [];

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
        'product_id' => 'string',
        'gallery' => 'string',
        'gallery_original' => 'string',
        'gallery_thumbnail' => 'string'

    ];


    /**
     * @param $value
     * @return mixed
     */
    public function getGalleryAttribute($value)
    {
        if ($this->is_file_exists($value))
            return \Illuminate\Support\Facades\Storage::url($value);
        else
            return asset(config('constants.image.default_img'));
    }


    /**
     * @param $value
     * @return mixed
     */
    public function getGalleryThumbnailAttribute($value)
    {
        if ($this->is_file_exists($value))
            return \Illuminate\Support\Facades\Storage::url($value);
        else
            return asset(config('constants.image.default_img'));
    }





    /**
     * Add ProductGallery
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateProductGallery($query, $request)
    {
        $productGallery = ProductGallery::create($request->all());

        if ($request->hasFile('gallery')) {
            $realPath = 'product_gallery/' . $productGallery->id;

            $resizeImages = $productGallery->resizeImages($request->file('gallery'), $realPath, 100, 100);

            $productGallery->update([
                'gallery' => $resizeImages['image'],
                'gallery_original' => $resizeImages['original'],
                'gallery_thumbnail' => $resizeImages['thumbnail']
            ]);
        }



        return \App\Models\User::GetMessage(new ProductGalleryResource($productGallery), config('constants.messages.create_success'));
    }

    /**
     * Update ProductGallery
     * @param Request $request
     * @param ProductGallery $productGallery
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateProductGallery($query, $request, $productGallery)
    {
        $data = $request->all();

        if ($request->hasFile('gallery')) {
            $realPath = 'product_gallery/' . $productGallery->id;

            \Illuminate\Support\Facades\Storage::deleteDirectory('/public/' . $realPath);

            $resizeImages = $productGallery->resizeImages($request->file('gallery'), $realPath, 100, 100);

            $data['gallery'] = $resizeImages['image'];
            $data['gallery_original'] = $resizeImages['original'];
            $data['gallery_thumbnail'] = $resizeImages['thumbnail'];
        }


        $productGallery->update($data);

        return \App\Models\User::GetMessage(new ProductGalleryResource($productGallery), config('constants.messages.update_success'));
    }

    /**
     * Delete ProductGallery
     *
     * @param Request $request
     * @param ProductGallery $productGallery
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteProductGallery($query, $request, $productGallery)
    {


        $this->singleImageDelete($productGallery, "product_gallery/"); // Delete image

        $productGallery->delete();

        return new DataTrueResource($productGallery, config('constants.messages.delete_success'));
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

            ProductGallery::whereIn('id', $request->id)->get()->each(function ($productGallery) {


                $this->singleImageDelete($productGallery, "product_gallery/"); // Delete image

                $productGallery->delete();
            });



            return new DataTrueResource(true, config('constants.messages.delete_success'));
        } else {
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }
}