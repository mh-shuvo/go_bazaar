<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInventoriesTable extends Migration {

	public function up()
	{
		Schema::create('inventories', function(Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('order_id')->nullable();
			$table->mediumInteger('product_id');
			$table->mediumInteger('client_id')->nullable();
			$table->mediumInteger('supplier_id');
			$table->tinyInteger('type');
			$table->double('debit')->default('0');
			$table->double('credit')->default('0');
			$table->double('selling_price')->nullable();
			$table->timestamps();
			$table->bigInteger('created_by');
			$table->string('created_by_ip', 15);
			$table->mediumInteger('updated_by')->nullable();
			$table->string('updated_by_ip', 15)->nullable();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('inventories');
	}
}