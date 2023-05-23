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
use App\Models\CategoryProduct;

class Category extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = ['id', 'name', 'description', 'parent_id', 'step','featured_image', 'category_status'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = ['id', 'name', 'description', 'featured_image', 'category_status'];

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
    public $light = ['id', 'name', 'description', 'featured_image', 'category_status'];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = ['products'];

    /**
     * @var array
     */
    public $sortable = ['categories.created_at', 'categories.id', 'name', 'description', 'featured_image', 'category_status'];

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
    public $type_sortable = ['category_status'];

    /**
     * @var array
     */
    public $type_enum = [
        ['constants.category.category_status_enum.inactive', 'constants.category.category_status_enum.active']
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
        ['constants.category.category_status.0', 'constants.category.category_status.1']
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
        'parent_id' => 'string',
        'name' => 'string',
        'step' => 'string',
        'description' => 'string',
        'featured_image' => 'string',
        'category_status' => 'string',

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

        // $request['parent_id'] = (int)$request->parent_id;

        // $Category = new Category();
        // $Category->name       = $request->name;
        // $Category->description = $request->description;
        // $Category->parent_id = $request->parent_id;
        // $Category->save();
        // // $category = Category::create($request->all());


        // if ($request->hasFile('featured_image')) {
        //     $realPath = 'category/' . $category->id;
        //     $resizeImages = $category->resizeImages($request->file('featured_image'), $realPath, 100, 100);

        //     $category->update([
        //         'category_id'    => $category->id,
        //         'featured_image' => $resizeImages['image']
        //         // 'profile_original' => $request->get('original'),
        //         // 'profile_thumbnail' => $resizeImages['thumbnail']
        //     ]);
        // }

        // return \App\Models\User::GetMessage(new CategoryResource($category), config('constants.messages.create_success'));
    }

    /**
     * Update Category
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateCategory($query, $request, $category)
    {
        $request['parent_id'] = (int)$request->parent_id;
        $data = $request->all();

        if ($request->hasFile('featured_image')) {
            $realPath = 'category/' . $category->id;
            $resizeImages = $category->resizeImages($request->file('featured_image'), $realPath, 100, 100);

            $data['featured_image'] = $resizeImages['image'];
            //     // $data['profile_original'] = $request->get('profile_image_original');
            //     // $data['profile_thumbnail'] = $resizeImages['thumbnail'];
        }

        $category->update($data);

        return \App\Models\User::GetMessage(new CategoryResource($category), config('constants.messages.update_success'));
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categories()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subCategories()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->select(['id', 'parent_id','name', 'description', 'featured_image']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(CategoryProduct::class, 'category_id');
    }

     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subcategory_products()
    {
        return $this->hasMany(Product::class, 'sub_category_id');
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
        $inUse = Category::commonCodeForDeleteModelRestrictions([CategoryProduct::class], 'category_id', $category->id);
        if (!empty($inUse)) {
            $Category = Category::where('id', $category->id)->first();
            return User::GetError($Category->name . " can't be deleted as the [" . implode(",", $inUse) . "] are associated with it. Please remove the category mapping with [" . implode(",", $inUse) . "] in order to delete the category.");
        }

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
            $categoryname = "";
            $inUses = [];
            foreach ($request->id as $categoryId) {
                $inUse = Category::commonCodeForDeleteModelRestrictions([CategoryProduct::class], 'category_id', $categoryId);
                $Category = Category::where('id', $categoryId)->first();
                if (!empty($inUse))
                    $categoryname .= $Category->name . ", ";
                $inUses[] = implode(",", $inUse);
            }
            $categorytrime = trim($categoryname, ", ");
            $arr = array_diff(array_unique($inUses), array(""));
            if ($categorytrime != "") {
                return User::GetError($categorytrime . " can't be deleted as the [" . implode(",", $arr) . "] are associated with it. Please remove the category mapping with [" . implode(",", $arr) . "] in order to delete the categories.");
            } else {
                Category::whereIn('id', $request->id)->delete();
                return new DataTrueResource(true, config('constants.messages.delete_success'));
            }
        } else {
            return response()->json(['error' => config('constants.messages.delete_multiple_error')], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
