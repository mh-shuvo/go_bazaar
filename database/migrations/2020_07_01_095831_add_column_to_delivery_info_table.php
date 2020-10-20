<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToDeliveryInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_info', function (Blueprint $table) {
            $table->boolean("status", 1)->after("order_id")->default(0)->comment("0=pending, 1=deliverd, 2=cancel");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_info', function (Blueprint $table) {
            $table->dropColumn("status");
        });
    }
}
