<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments("id")->unique()->index()->comment("AUTO_INCREMENT");
            $table->unsignedInteger("user_id")->index()->comment("Users table id");
            $table->foreign("user_id")->references("id")->on("users");
            $table->unsignedInteger("quantity")->nullable();
            $table->decimal("gst",15,4)->nullable();
            $table->decimal("payment_amount",15,4)->nullable();
            $table->enum("order_status",['0','1','2','3','4'])->comment("0 => 'Pending', 1 => 'Inprocess', 2=>'Cancel', 3=>'Completed', 4=>'Return'");
            $table->text("order_status_remark")->nullable()->comment("Remarks from admin");
            $table->text("user_remark")->nullable()->comment("Remarks from admin");
            $table->unsignedInteger('created_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->nullable()->comment('Users table ID');
            $table->unsignedInteger('deleted_by')->nullable()->comment('Users table ID');
            $table->timestamps();
            $table->softDeletes();
        });

        
        

        \Illuminate\Support\Facades\DB::table("permissions")->insert([

            array("id" => 48 , "name" => "orders","label" => "Order","guard_name" => "root","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),

            array("id" => 49 , "name" => "index-orders","label" => "View","guard_name" => "orders","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 50 , "name" => "show-orders","label" => "Show","guard_name" => "orders","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 51 , "name" => "store-orders","label" => "Add","guard_name" => "orders","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 52 , "name" => "update-orders","label" => "Update","guard_name" => "orders","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 53 , "name" => "destroy-orders","label" => "Delete","guard_name" => "orders","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 54 , "name" => "deleteAll-orders","label" => "Delete All","guard_name" => "orders","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 55 , "name" => "importBulk-orders","label" => "Import","guard_name" => "orders","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 56 , "name" => "export-orders","label" => "Export","guard_name" => "orders","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
            array("id" => 57 , "name" => "deleteProductId-orders","label" => "Delete Product Id","guard_name" => "orders","created_at" => config("constants.calender.date_time"),"updated_at" => config("constants.calender.date_time")),
        ]);

            
            

        \Illuminate\Support\Facades\DB::table("permission_role")->insert([
            
            array("role_id" => 1,"permission_id" => 48),
            
            array("role_id" => 1,"permission_id" => 49),
            
            array("role_id" => 1,"permission_id" => 50),
            
            array("role_id" => 1,"permission_id" => 51),
            
            array("role_id" => 1,"permission_id" => 52),
            
            array("role_id" => 1,"permission_id" => 53),
            
            array("role_id" => 1,"permission_id" => 54),
            
            array("role_id" => 1,"permission_id" => 55),
            
            array("role_id" => 1,"permission_id" => 56),
            
            array("role_id" => 1,"permission_id" => 57),
            
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
