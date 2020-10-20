<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationsTable extends Migration {

	public function up()
	{
		Schema::create('locations', function(Blueprint $table) {
			$table->increments('id');
			$table->mediumInteger('parent_id')->unsigned()->nullable();
			$table->string('name', 191);
			$table->tinyInteger('type')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('locations');
	}
}