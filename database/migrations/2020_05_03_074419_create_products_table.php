<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	public function up()
	{
		Schema::create('products', function(Blueprint $table) {
			$table->increments('id');
			$table->smallInteger('upazila_id');
			$table->mediumInteger('union_id');
			$table->integer('supplier_id');
			$table->mediumInteger('category_id');
			$table->integer('sub_category_id');
			$table->mediumInteger('unit_id');
			$table->string('name', 100);
			$table->string('picture', 100)->nullable();
			$table->mediumInteger('created_by');
			$table->string('created_by_ip', 15);
			$table->mediumInteger('updated_by')->nullable();
			$table->string('updated_by_ip', 15);
			$table->timestamp('timestamps');
		});
	}

	public function down()
	{
		Schema::drop('products');
	}
}