<?php

namespace App\Models;
use App\Http\Resources\DataTrueResource;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use App\Traits\UploadTrait;
use App\Http\Resources\CategoryProductResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class CategoryProduct extends Model
{
    use SoftDeletes, Scopes, CreatedbyUpdatedby, HasFactory, UploadTrait;

    /**
     * @var array
     */
    protected $fillable = [ 'id', 'product_id', 'category_id' ];

    /**
     * Activity log array
     *
     * @var array
     */
    public $activity_log = [  ];

    /**
     * Log Activity relationships array
     *
     * @var array
     */
    public $log_relations = [  ];

    /**
     * Lightweight response variable
     *
     * @var array
     */
    public $light = [ 'id', 'product_id', 'category_id' ];

    /**
     * Related permission array
     *
     * @var array
     */
    public $related_permission = [  ];

    /**
     * @var array
     */
    public $sortable = [ 'category_products.created_at', 'category_products.id', 'product_id', 'category_id' ];

    /**
     * @var array
     */
    public $foreign_sortable = [  ];

    /**
     * @var array
     */
    public $foreign_table = [  ];

    /**
     * @var array
     */
    public $foreign_key = [  ];

    /**
     * @var array
     */
    public $foreign_method = [  ];

    /**
     * @var array
     */
    public $type_sortable = [  ];

    /**
     * @var array
     */
    public $type_enum = [
            
    ];

    /**
     * @var array
     */
    public $type_enum_text = [
            
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
    protected $hidden = [  ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

            'id'=>'string', 
            'product_id'=>'string', 
            'category_id'=>'string'

    ];

    

    

    /**
     * Add CategoryProduct
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeCreateCategoryProduct($query, $request){
        $categoryProduct = CategoryProduct::create($request->all());
        
        
        
        return \App\Models\User::GetMessage(new CategoryProductResource($categoryProduct), config('constants.messages.create_success'));
    }

    /**
     * Update CategoryProduct
     * @param Request $request
     * @param CategoryProduct $categoryProduct
     * @return \Illuminate\Http\JsonResponse
     */
    public function scopeUpdateCategoryProduct($query, $request, $categoryProduct){
        $data = $request->all();
        
        
        $categoryProduct->update($data);
        
        return \App\Models\User::GetMessage(new CategoryProductResource($categoryProduct), config('constants.messages.update_success'));
    }

    /**
     * Delete CategoryProduct
     *
     * @param Request $request
     * @param CategoryProduct $categoryProduct
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function scopeDeleteCategoryProduct($query, $request, $categoryProduct){
       
       
       $categoryProduct->delete();
       
       return new DataTrueResource($categoryProduct,config('constants.messages.delete_success'));
    }

    /**
     * Multiple Delete
     * @param $query
     * @param $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function scopeDeleteAll($query,$request){
        if(!empty($request->id)) {

            CategoryProduct::whereIn('id', $request->id)->get()->each(function($categoryProduct) {
                
                
                $categoryProduct->delete();
            });

            

            return new DataTrueResource(true,config('constants.messages.delete_success'));
        }
        else{
            return User::GetError(config('constants.messages.delete_multiple_error'));
        }
    }

    
}
