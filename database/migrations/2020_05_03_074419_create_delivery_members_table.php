<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeliveryMembersTable extends Migration {

	public function up()
	{
		Schema::create('delivery_members', function(Blueprint $table) {
			$table->increments('id');
			$table->mediumInteger('upazila_id');
			$table->mediumInteger('union_id');
			$table->string('name', 100);
			$table->string('email', 50)->nullable();
			$table->string('mobile', 15);
			$table->text('address')->nullable();
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
		Schema::drop('delivery_members');
	}
}