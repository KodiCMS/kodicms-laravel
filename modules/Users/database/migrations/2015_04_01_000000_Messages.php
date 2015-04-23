<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Messages extends Migration
{
	public function up()
	{
		Schema::create('messages', function (Blueprint $table) {
			$table->increments('id');
			$table->timestamps();

			$table->unsignedInteger('from_user_id')->index();
			$table->string('title');
			$table->text('message');
		});
	}

	public function down()
	{
		Schema::dropIfExists('messages');
	}
}