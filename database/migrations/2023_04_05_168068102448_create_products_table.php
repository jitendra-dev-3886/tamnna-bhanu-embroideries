<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments("id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->string("name",191)->nullable();
            $table->double("price",10,2)->nullable();
            $table->text("description")->nullable();
            $table->string("item_code",191)->nullable();
            $table->unsignedInteger("category_id")->index()->nullable()->comment("Category table ID");
            $table->foreign("category_id")->references("id")->on("categories");
            $table->enum("available_status",['0','1'])->nullable()->comment("0 => 'Not-available', 1 => 'Available'");
            $table->unsignedInteger("stock")->nullable();
            $table->string("featured_image",500)->nullable();
            $table->unsignedInteger('created_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('deleted_by')->nullable()->comment('Users table ID');
            $table->timestamps();
            $table->softDeletes();
        });

        
        

        \Illuminate\Support\Facades\DB::table("permissions")->insert([

            array("id" => 38 , "name" => "products","label" => "Product","guard_name" => "root","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 39 , "name" => "index-products","label" => "View","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 40 , "name" => "show-products","label" => "Show","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 41 , "name" => "store-products","label" => "Add","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 42 , "name" => "update-products","label" => "Update","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 43 , "name" => "destroy-products","label" => "Delete","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 44 , "name" => "deleteAll-products","label" => "Delete All","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 45 , "name" => "importBulk-products","label" => "Import","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 46 , "name" => "export-products","label" => "Export","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 47 , "name" => "deleteGallery-products","label" => "Delete Gallery","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);

            
            

        \Illuminate\Support\Facades\DB::table("permission_role")->insert([
            
            array("role_id" => 1,"permission_id" => 38),
            
            array("role_id" => 1,"permission_id" => 39),
            
            array("role_id" => 1,"permission_id" => 40),
            
            array("role_id" => 1,"permission_id" => 41),
            
            array("role_id" => 1,"permission_id" => 42),
            
            array("role_id" => 1,"permission_id" => 43),
            
            array("role_id" => 1,"permission_id" => 44),
            
            array("role_id" => 1,"permission_id" => 45),
            
            array("role_id" => 1,"permission_id" => 46),
            
            array("role_id" => 1,"permission_id" => 47),
            
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
