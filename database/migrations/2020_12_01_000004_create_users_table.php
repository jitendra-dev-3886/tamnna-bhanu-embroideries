<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments("id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->string("email",191)->nullable();
            $table->string("password",191)->nullable();
            $table->unsignedInteger("role_id")->index()->nullable()->comment("roles table id");
            $table->foreign("role_id")->references("id")->on("roles");
            $table->timestamp("email_verified_at")->nullable();
            $table->string("remember_token",191)->nullable();
            $table->unsignedInteger("last_login_time")->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->unsignedInteger('created_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('deleted_by')->nullable()->comment('Users table ID');
            $table->timestamps();
            $table->softDeletes();
        });

        \Illuminate\Support\Facades\DB::table('users')->insert([
            [
                 
                 'email'=>'admin@gmail.com',
                 'password'=>'$2y$10$gCb4kNHFsGHu.hgvMo5.W.sI/my48gC9OTVSbwTT7aOnY/kpidUHK', // 123456
                 //'user_type'=>'0',
                 'role_id'=>'1',
                 'status'=>'1',
                 'email_verified_at'=> config('constants.calender.date_time'),
                 'created_at' => config('constants.calender.date_time'),
                 'updated_at' => config('constants.calender.date_time')
            ],
            [
                 
                 'email'=>'subadmin@gmail.com',
                 'password'=>'$2y$10$gCb4kNHFsGHu.hgvMo5.W.sI/my48gC9OTVSbwTT7aOnY/kpidUHK', // 123456
                 //'user_type'=>'0',
                 'role_id'=>'2',
                 'status'=>'1',
                 'email_verified_at'=> config('constants.calender.date_time'),
                 'created_at' => config('constants.calender.date_time'),
                 'updated_at' => config('constants.calender.date_time')
            ]
        ]);

        

        \Illuminate\Support\Facades\DB::table("permissions")->insert([

            array("id" => 20,"name" => "users","label" => "User","guard_name" => "root","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 21,"name" => "index-users","label" => "View","guard_name" => "users","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 22,"name" => "show-users","label" => "Show","guard_name" => "users","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 23,"name" => "store-users","label" => "Add","guard_name" => "users","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 24,"name" => "update-users","label" => "Update","guard_name" => "users","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 25,"name" => "destroy-users","label" => "Delete","guard_name" => "users","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 26,"name" => "deleteAll-users","label" => "Delete All","guard_name" => "users","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 27,"name" => "importBulk-users","label" => "Import","guard_name" => "users","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 28,"name" => "export-users","label" => "Export","guard_name" => "users","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);
        
            

        \Illuminate\Support\Facades\DB::table("permission_role")->insert([

            array("role_id" => 1,"permission_id" => 20),
            array("role_id" => 1,"permission_id" => 21),
            array("role_id" => 1,"permission_id" => 22),
            array("role_id" => 1,"permission_id" => 23),
            array("role_id" => 1,"permission_id" => 24),
            array("role_id" => 1,"permission_id" => 25),
            array("role_id" => 1,"permission_id" => 26),
            array("role_id" => 1,"permission_id" => 27),
            array("role_id" => 1,"permission_id" => 28),

        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
