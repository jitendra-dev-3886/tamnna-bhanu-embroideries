<?php

namespace App\Models;

use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\HomeBannerResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class HomeBanner extends Model
{
    protected $table = 'homebanners';

    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = ['id', 'name', 'featured_image', 'banner_status'];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = ['id', 'name', 'featured_image', 'banner_status'];

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
    public $light = ['id', 'name', 'featured_image', 'banner_status'];

    /**
     * Related permission array
     *
     * @var array
     */
    // public $related_permission = ['products'];

    /**
     * @var array
     */
    public $sortable = ['homebanners.created_at', 'homebanners.id', 'name', 'featured_image', 'banner_status'];

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
    public $type_sortable = ['banner_status'];

    /**
     * @var array
     */
    public $type_enum = [
        ['constants.homebanner.banner_status_enum.inactive', 'constants.homebanner.banner_status_enum.active']
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
        ['constants.homebanner.banner_status.0', 'constants.homebanner.banner_status.1']
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

        'id'             => 'string',
        'name'           => 'string',
        'featured_image' => 'string',
        'banner_status'  => 'string',

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
     * Add HomeBanner
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateHomeBanner($query, $request)
    {
        $homebanner = HomeBanner::create($request->all());

        if ($request->hasFile('featured_image')) {
            $realPath = 'homebanner/' . $homebanner->id;
            $resizeImages = $homebanner->resizeImages($request->file('featured_image'), $realPath, 100, 100);

            $homebanner->update([
                'homebanner_id'  => $homebanner->id,
                'featured_image' => $resizeImages['image']
                // 'profile_original' => $request->get('original'),
                // 'profile_thumbnail' => $resizeImages['thumbnail']
            ]);
        }

        return \App\Models\User::GetMessage(new HomeBannerResource($homebanner), config('constants.messages.create_success'));
    }

    /**
     * Update HomeBanner
     * @param Request $request
     * @param HomeBanner $homebanner
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateHomeBanner($query, $request, $homebanner)
    {
        $data = $request->all();

        if ($request->hasFile('featured_image')) {
            $realPath = 'homebanner/' . $homebanner->id;
            $resizeImages = $homebanner->resizeImages($request->file('    '), $realPath, 100, 100);

            $data['featured_image'] = $resizeImages['image'];
            //     // $data['profile_original'] = $request->get('profile_image_original');
            //     // $data['profile_thumbnail'] = $resizeImages['thumbnail'];
        }

        $homebanner->update($data);

        return \App\Models\User::GetMessage(new HomeBannerResource($homebanner), config('constants.messages.update_success'));
    }

    /**
     * Delete HomeBanner
     *
     * @param Request $request
     * @param HomeBanner $homebanner
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteHomeBanner($query, $request, $homebanner)
    {
        $homebanner->delete();
        return new DataTrueResource($homebanner, config('constants.messages.delete_success'));
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

            HomeBanner::whereIn('id', $request->id)->get()->each(function ($homebanner) {
                $homebanner->delete();
            });

            return new DataTrueResource(true, config('constants.messages.delete_success'));
        } else {
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }
}