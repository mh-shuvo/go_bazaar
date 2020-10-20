<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSupplierTypesTable extends Migration {

	public function up()
	{
		Schema::create('supplier_types', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 150);
			$table->timestamps();
			$table->mediumInteger('created_by');
			$table->string('created_by_ip', 15);
			$table->mediumInteger('updated_by');
			$table->string('updated_by_ip', 15);
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('supplier_types');
	}
}