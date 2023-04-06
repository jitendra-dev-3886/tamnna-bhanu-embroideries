<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments("id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->unsignedInteger("user_id")->index()->nullable()->comment("Users table id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedInteger("product_id")->index()->nullable()->comment("Products table id");
            $table->foreign("product_id")->references("id")->on("products");
            $table->unsignedInteger("quantity")->nullable();
            $table->unsignedInteger('created_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('deleted_by')->nullable()->comment('Users table ID');
            $table->timestamps();
            $table->softDeletes();
        });

        
        

        \Illuminate\Support\Facades\DB::table("permissions")->insert([

            array("id" => 58 , "name" => "carts","label" => "Cart","guard_name" => "root","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 59 , "name" => "index-carts","label" => "View","guard_name" => "carts","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 60 , "name" => "show-carts","label" => "Show","guard_name" => "carts","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 61 , "name" => "store-carts","label" => "Add","guard_name" => "carts","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 62 , "name" => "update-carts","label" => "Update","guard_name" => "carts","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 63 , "name" => "destroy-carts","label" => "Delete","guard_name" => "carts","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 64 , "name" => "deleteAll-carts","label" => "Delete All","guard_name" => "carts","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 65 , "name" => "importBulk-carts","label" => "Import","guard_name" => "carts","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 66 , "name" => "export-carts","label" => "Export","guard_name" => "carts","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);

            
            

        \Illuminate\Support\Facades\DB::table("permission_role")->insert([
            
            array("role_id" => 1,"permission_id" => 58),
            
            array("role_id" => 1,"permission_id" => 59),
            
            array("role_id" => 1,"permission_id" => 60),
            
            array("role_id" => 1,"permission_id" => 61),
            
            array("role_id" => 1,"permission_id" => 62),
            
            array("role_id" => 1,"permission_id" => 63),
            
            array("role_id" => 1,"permission_id" => 64),
            
            array("role_id" => 1,"permission_id" => 65),
            
            array("role_id" => 1,"permission_id" => 66),
            
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
