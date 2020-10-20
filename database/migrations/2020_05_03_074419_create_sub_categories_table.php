<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('sub_categories', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('parent_id');
			$table->string('name', 100);
			$table->timestamps();
			$table->bigInteger('created_by');
			$table->string('created_by_ip', 15);
			$table->bigInteger('updated_by')->nullable();
			$table->string('updated_by_ip', 15)->nullable();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('sub_categories');
	}
}