<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class NewsContent extends Migration
{
	public function up()
	{
		Schema::create('news_content', function (Blueprint $table) {
			$table->increments('news_id');
			$table->string('description');
			$table->string('description_filtered');


			$table->string('content');
			$table->string('content_filtered');
		});
	}

	public function down()
	{
		Schema::dropIfExists('news_content');
	}
}
