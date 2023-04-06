<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments("id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->string("name",191)->nullable();
            $table->text("description")->nullable();
            $table->string("featured_image",500)->nullable();
            $table->unsignedInteger('created_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('deleted_by')->nullable()->comment('Users table ID');
            $table->timestamps();
            $table->softDeletes();
        });

        
        

        \Illuminate\Support\Facades\DB::table("permissions")->insert([

            array("id" => 29 , "name" => "categories","label" => "Category","guard_name" => "root","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 30 , "name" => "index-categories","label" => "View","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 31 , "name" => "show-categories","label" => "Show","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 32 , "name" => "store-categories","label" => "Add","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 33 , "name" => "update-categories","label" => "Update","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 34 , "name" => "destroy-categories","label" => "Delete","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 35 , "name" => "deleteAll-categories","label" => "Delete All","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 36 , "name" => "importBulk-categories","label" => "Import","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 37 , "name" => "export-categories","label" => "Export","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);

            
            

        \Illuminate\Support\Facades\DB::table("permission_role")->insert([
            
            array("role_id" => 1,"permission_id" => 29),
            
            array("role_id" => 1,"permission_id" => 30),
            
            array("role_id" => 1,"permission_id" => 31),
            
            array("role_id" => 1,"permission_id" => 32),
            
            array("role_id" => 1,"permission_id" => 33),
            
            array("role_id" => 1,"permission_id" => 34),
            
            array("role_id" => 1,"permission_id" => 35),
            
            array("role_id" => 1,"permission_id" => 36),
            
            array("role_id" => 1,"permission_id" => 37),
            
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
