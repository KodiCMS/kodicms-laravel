<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EmailQueues extends Migration
{
	public function up()
	{
		Schema::create('email_queues', function (Blueprint $table) {
			$table->increments('id');
			$table->timestamps();

			$table->enum('status', ['pending', 'sent', 'failed']);
			$table->json('parameters');
			$table->string('message_type', 5);
			$table->text('body');
			$table->unsignedInteger('attempts')->default(0);
		});
	}

	public function down()
	{
		Schema::dropIfExists('email_queues');
	}
}
