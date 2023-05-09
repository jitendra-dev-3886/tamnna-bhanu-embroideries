<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteImagePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::table("permissions")->insert([


            array("id" => 94 , "name" => "deleteProductImage-products","label" => "Delete product image","guard_name" => "products","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 95 , "name" => "deleteCategoryImage-categories","label" => "Delete category image","guard_name" => "categories","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 96 , "name" => "deleteBannerImage-homebanners","label" => "Update category homebanner","guard_name" => "homebanners","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);




        \Illuminate\Support\Facades\DB::table("permission_role")->insert([



            array("role_id" => 1,"permission_id" => 94),
            array("role_id" => 1,"permission_id" => 95),
            array("role_id" => 1,"permission_id" => 96),
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
