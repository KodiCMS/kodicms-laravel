<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Jobs extends Migration
{
	public function up()
	{
		Schema::create('jobs', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('task_name');

			$table->dateTime('start_on');
			$table->dateTime('send_on');

			$table->dateTime('last_run');
			$table->dateTime('next_run')->index();

			$table->integer('interval');
			$table->string('crontime', 100);

			// TODO: перенести статус по умолчанию в класс
			$table->tinyInteger('status')->default(1);
			$table->integer('attempts');
			$table->timestamp('created_at');
		});
	}

	public function down()
	{
		Schema::dropIfExists('jobs');
	}
}
