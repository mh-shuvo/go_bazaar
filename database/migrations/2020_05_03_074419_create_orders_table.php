<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->mediumInteger('union_id');
			$table->bigInteger('order_id')->unique();
			$table->mediumInteger('client_id');
			$table->double('total_amount');
			$table->double('discount')->default('0');
			$table->double('net_amount');
			$table->enum('status', array('pending', 'confirmed', 'deliverd', 'rejected'));
			$table->text('shipping_address')->nullable();
			$table->bigInteger('created_by');
			$table->timestamps();
			$table->string('created_by_ip', 15);
			$table->bigInteger('updated_by')->nullable();
			$table->string('updated_by_ip', 15)->nullable();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}