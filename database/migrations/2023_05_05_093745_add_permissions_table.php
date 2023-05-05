<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table("permissions")->insert([


            array("id" => 90 , "name" => "updateProductImage-products","label" => "Update product image","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 91 , "name" => "updateProductGallery-products","label" => "Update product gallery","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 92 , "name" => "updateCategoryImage-categories","label" => "Update category image","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 93 , "name" => "updateBannerImage-homebanners","label" => "Update category homebanner","guard_name" => "homebanners","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);




        \Illuminate\Support\Facades\DB::table("permission_role")->insert([



            array("role_id" => 1,"permission_id" => 90),
            array("role_id" => 1,"permission_id" => 91),
            array("role_id" => 1,"permission_id" => 92),
            array("role_id" => 1,"permission_id" => 93),

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
