<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class JobLogs extends Migration
{
	public function up()
	{
		Schema::create('job_logs', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('job_id')->index();
			$table->timestamp('created_at');

			// TODO: перенести статус по умолчанию в класс
			$table->tinyInteger('status')->default(1);
		});
	}

	public function down()
	{
		Schema::dropIfExists('job_logs');
	}
}
