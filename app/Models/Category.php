<?php

namespace App\Models;

use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Category extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = ['id', 'name', 'description', 'featured_image'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = ['id', 'name', 'description', 'featured_image'];

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
    public $light = ['id', 'name', 'description', 'featured_image'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = ['products'];

    /**
     * @var array
     */
    public $sortable = ['categories.created_at', 'categories.id', 'name', 'description', 'featured_image'];

    /**
     * @var array
     */
    public $foreign_sortable = [];

    /**
     * @var array
     */
    public $foreign_table = [];

    /**
     * @var array
     */
    public $foreign_key = [];

    /**
     * @var array
     */
    public $foreign_method = [];

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
        'name' => 'string',
        'description' => 'string',
        'featured_image' => 'string'

    ];

    /**
     * @param $value
     * @return mixed
     */
    public function getFeaturedImageAttribute($value)
    {
        // dd($value);
        if ($this->is_file_exists($value))
            return \Illuminate\Support\Facades\Storage::url($value);
        else
            return asset(config('constants.image.default_img'));
    }

    /**
     * Add Category
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateCategory($query, $request)
    {
        $category = Category::create($request->all());

        if ($request->hasFile('featured_image')) {
            $realPath = 'category/' . $category->id;
            $resizeImages = $category->resizeImages($request->file('featured_image'), $realPath, 100, 100);

            $category->update([
                'category_id'    => $category->id,
                'featured_image' => $resizeImages['image']
                // 'profile_original' => $request->get('original'),
                // 'profile_thumbnail' => $resizeImages['thumbnail']
            ]);
        }

        return \App\Models\User::GetMessage(new CategoryResource($category), config('constants.messages.create_success'));
    }

    /**
     * Update Category
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateCategory($query, $request, $category)
    {
        $data = $request->all();

        if ($request->hasFile('featured_image')) {
            $realPath = 'category/' . $category->id . '/';
            $resizeImages = $category->resizeImages($request->file('featured_image'), $realPath, 100, 100);

            $data['featured_image'] = $resizeImages['image'];
            //     // $data['profile_original'] = $request->get('profile_image_original');
            //     // $data['profile_thumbnail'] = $resizeImages['thumbnail'];
        }

        $category->update($data);

        return \App\Models\User::GetMessage(new CategoryResource($category), config('constants.messages.update_success'));
    }

    /**
     * Delete Category
     *
     * @param Request $request
     * @param Category $category
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteCategory($query, $request, $category)
    {


        $category->delete();

        return new DataTrueResource($category, config('constants.messages.delete_success'));
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

            Category::whereIn('id', $request->id)->get()->each(function ($category) {


                $category->delete();
            });



            return new DataTrueResource(true, config('constants.messages.delete_success'));
        } else {
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }
}
