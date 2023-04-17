<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homebanners', function (Blueprint $table) {
            $table->increments("id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->string("name", 191)->nullable();
            $table->string("featured_image", 500)->nullable();
            $table->enum("banner_status", ['0', '1'])->nullable()->comment("0 => 'Inactive', 1 => 'Active'");
            $table->unsignedInteger('created_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('deleted_by')->nullable()->comment('Users table ID');
            $table->timestamps();
            $table->softDeletes();
        });


        \Illuminate\Support\Facades\DB::table("permissions")->insert([

            array("id" => 76, "name" => "homebanners", "label" => "HomeBanner", "guard_name" => "root", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),

            array("id" => 77, "name" => "index-homebanners", "label" => "View", "guard_name" => "homebanners", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 78, "name" => "show-homebanners", "label" => "Show", "guard_name" => "homebanners", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 79, "name" => "store-homebanners", "label" => "Add", "guard_name" => "homebanners", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 80, "name" => "update-homebanners", "label" => "Update", "guard_name" => "homebanners", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 81, "name" => "destroy-homebanners", "label" => "Delete", "guard_name" => "homebanners", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 82, "name" => "deleteAll-homebanners", "label" => "Delete All", "guard_name" => "homebanners", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 83, "name" => "importBulk-homebanners", "label" => "Import", "guard_name" => "homebanners", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 84, "name" => "export-homebanners", "label" => "Export", "guard_name" => "homebanners", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
        ]);

        \Illuminate\Support\Facades\DB::table("permission_role")->insert([
            array("role_id" => 1, "permission_id" => 76),
            array("role_id" => 1, "permission_id" => 77),
            array("role_id" => 1, "permission_id" => 78),
            array("role_id" => 1, "permission_id" => 79),
            array("role_id" => 1, "permission_id" => 80),
            array("role_id" => 1, "permission_id" => 81),
            array("role_id" => 1, "permission_id" => 82),
            array("role_id" => 1, "permission_id" => 83),
            array("role_id" => 1, "permission_id" => 84),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homebanners');
    }
}