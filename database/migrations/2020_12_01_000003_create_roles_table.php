<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments("id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->string("name",191)->nullable();
            $table->string("guard_name",191)->nullable();
            $table->string("landing_page",191)->nullable();
            $table->unsignedInteger('created_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('deleted_by')->nullable()->comment('Users table ID');
            $table->timestamps();
            $table->softDeletes();
        });

        
        DB::table("roles")->insert(array(
            array("name" => "Administrator","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("name" => "Sub Admin","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ));
            
        

        \Illuminate\Support\Facades\DB::table("permissions")->insert([

            array("id" => 9,"name" => "roles","label" => "Role","guard_name" => "root","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 10,"name" => "index-roles","label" => "View","guard_name" => "roles","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 11,"name" => "show-roles","label" => "Show","guard_name" => "roles","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 12,"name" => "store-roles","label" => "Add","guard_name" => "roles","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 13,"name" => "update-roles","label" => "Update","guard_name" => "roles","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 14,"name" => "destroy-roles","label" => "Delete","guard_name" => "roles","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 15,"name" => "deleteAll-roles","label" => "Delete All","guard_name" => "roles","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 16,"name" => "export-roles","label" => "Export","guard_name" => "roles","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);
        
            

        \Illuminate\Support\Facades\DB::table("permission_role")->insert([

            array("role_id" => 1,"permission_id" => 9),
            array("role_id" => 1,"permission_id" => 10),
            array("role_id" => 1,"permission_id" => 11),
            array("role_id" => 1,"permission_id" => 12),
            array("role_id" => 1,"permission_id" => 13),
            array("role_id" => 1,"permission_id" => 14),
            array("role_id" => 1,"permission_id" => 15),
            array("role_id" => 1,"permission_id" => 16),

        ]);
        
            

        \Illuminate\Support\Facades\DB::table("permissions")->insert([

            array("id" => 17,"name" => "permission-role-mappings","label" => "Permission Role Mapping","guard_name" => "root","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 18,"name" => "setUnsetPermissionToRole-permissions","label" => "Set/Unset Permission","guard_name" => "permission-role-mappings","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 19,"name" => "getPermissionsByRole-roles","label" => "Permissions By Role","guard_name" => "permission-role-mappings","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);
        
            

        \Illuminate\Support\Facades\DB::table("permission_role")->insert([

            array("role_id" => 1,"permission_id" => 17),
            array("role_id" => 1,"permission_id" => 18),
            array("role_id" => 1,"permission_id" => 19),

        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
