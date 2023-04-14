<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->increments("id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->string("type", 255)->nullable();
            $table->string("label", 255)->nullable();
            $table->longText("message")->nullable();
            $table->string("status", 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        \Illuminate\Support\Facades\DB::table('sms_templates')->insert([
            [
                'type'    => '1',
                'label'   => 'OTP(English)',
                'message' => '{{users_otp}} is Your OTP for registering in Tamanna Bhanu Embroideries App. Regards, Team Tamanna Bhanu {{auto_otp_detection_code}}',
                'status' => '1'
            ]
        ]);



        \Illuminate\Support\Facades\DB::table("permissions")->insert([
            array("id" => 67, "name" => "sms_templates", "label" => "User", "guard_name" => "root", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 68, "name" => "index-sms_templates", "label" => "View", "guard_name" => "sms_templates", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 69, "name" => "show-sms_templates", "label" => "Show", "guard_name" => "sms_templates", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 70, "name" => "store-sms_templates", "label" => "Add", "guard_name" => "sms_templates", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 71, "name" => "update-sms_templates", "label" => "Update", "guard_name" => "sms_templates", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 72, "name" => "destroy-sms_templates", "label" => "Delete", "guard_name" => "sms_templates", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 73, "name" => "deleteAll-sms_templates", "label" => "Delete All", "guard_name" => "sms_templates", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 74, "name" => "importBulk-sms_templates", "label" => "Import", "guard_name" => "sms_templates", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
            array("id" => 75, "name" => "export-sms_templates", "label" => "Export", "guard_name" => "sms_templates", "created_at" => config("constants.calender.date_time"), "updated_at" => config("constants.calender.date_time")),
        ]);



        \Illuminate\Support\Facades\DB::table("permission_role")->insert([

            array("role_id" => 1, "permission_id" => 67),
            array("role_id" => 1, "permission_id" => 68),
            array("role_id" => 1, "permission_id" => 69),
            array("role_id" => 1, "permission_id" => 70),
            array("role_id" => 1, "permission_id" => 71),
            array("role_id" => 1, "permission_id" => 72),
            array("role_id" => 1, "permission_id" => 73),
            array("role_id" => 1, "permission_id" => 74),
            array("role_id" => 1, "permission_id" => 75),

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_templates');
    }
}
