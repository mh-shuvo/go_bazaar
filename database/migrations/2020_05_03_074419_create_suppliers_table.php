<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSuppliersTable extends Migration {

	public function up()
	{
		Schema::create('suppliers', function(Blueprint $table) {
			$table->increments('id');
			$table->mediumInteger('upazila_id');
			$table->mediumInteger('union_id');
			$table->mediumInteger('supplier_types');
			$table->string('name', 150);
			$table->string('shop_name', 150);
			$table->string('mobile', 15);
			$table->string('email', 100)->nullable();
			$table->text('address');
			$table->timestamps();
			$table->mediumInteger('created_by');
			$table->string('created_by_ip', 15);
			$table->mediumInteger('updated_by')->nullable();
			$table->string('updated_by_ip', 15)->nullable();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('suppliers');
	}
}