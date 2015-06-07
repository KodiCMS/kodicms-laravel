<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class News extends Migration
{
	public function up()
	{
		Schema::create('news', function (Blueprint $table) {
			$table->timestamps();

			$table->increments('id');
			$table->string('title');
			$table->string('slug')->unique();
			$table->integer('user_id')->index();
		});
	}

	public function down()
	{
		Schema::dropIfExists('news');
	}
}
