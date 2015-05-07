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

			$table->dateTime('date_start');
			$table->dateTime('date_end');

			$table->dateTime('last_run')->nullable();
			$table->dateTime('next_run')->index();

			$table->integer('interval');
			$table->string('crontime', 100);

			$table->tinyInteger('status');
			$table->integer('attempts');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('jobs');
	}
}
