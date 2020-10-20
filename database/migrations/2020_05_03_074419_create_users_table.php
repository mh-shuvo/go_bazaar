<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('upazila_id')->unsigned()->default('0');
			$table->integer('union_id')->unsigned()->default('0');
			$table->mediumInteger('record_id');
			$table->string('username', 20);
			$table->string('password', 100);
			$table->tinyInteger('user_type');
			$table->timestamps();
			$table->bigInteger('created_by');
			$table->bigInteger('updated_by')->nullable();
			$table->string('created_by_ip', 15);
			$table->string('updated_by_ip', 15)->nullable();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}