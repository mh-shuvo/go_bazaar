<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductRateTable extends Migration {

	public function up()
	{
		Schema::create('product_rate', function(Blueprint $table) {
			$table->increments('id');
			$table->smallInteger('upazila_id');
			$table->smallInteger('union_id');
			$table->mediumInteger('supplier_id');
			$table->mediumInteger('product_id');
			$table->double('rate');
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
		Schema::drop('product_rate');
	}
}