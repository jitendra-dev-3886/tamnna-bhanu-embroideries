<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments("id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->string("name",191)->nullable();
            $table->string("guard_name",191)->nullable();
            $table->string("label",191)->nullable();
            $table->unsignedInteger('created_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('deleted_by')->nullable()->comment('Users table ID');
            $table->timestamps();
            $table->softDeletes();
        });

        
        

        \Illuminate\Support\Facades\DB::table("permissions")->insert([

            array("id" => 1,"name" => "permissions","label" => "Permission","guard_name" => "root","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 2,"name" => "index-permissions","label" => "View","guard_name" => "permissions","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 3,"name" => "show-permissions","label" => "Show","guard_name" => "permissions","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 4,"name" => "store-permissions","label" => "Add","guard_name" => "permissions","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 5,"name" => "update-permissions","label" => "Update","guard_name" => "permissions","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 6,"name" => "destroy-permissions","label" => "Delete","guard_name" => "permissions","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 7,"name" => "deleteAll-permissions","label" => "Delete All","guard_name" => "permissions","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 8,"name" => "export-permissions","label" => "Export","guard_name" => "permissions","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);
        
            

        \Illuminate\Support\Facades\DB::table("permission_role")->insert([

            array("role_id" => 1,"permission_id" => 1),
            array("role_id" => 1,"permission_id" => 2),
            array("role_id" => 1,"permission_id" => 3),
            array("role_id" => 1,"permission_id" => 4),
            array("role_id" => 1,"permission_id" => 5),
            array("role_id" => 1,"permission_id" => 6),
            array("role_id" => 1,"permission_id" => 7),
            array("role_id" => 1,"permission_id" => 8),

        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
