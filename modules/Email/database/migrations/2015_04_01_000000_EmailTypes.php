<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EmailTypes extends Migration
{
	public function up()
	{
		Schema::create('email_types', function (Blueprint $table) {
			$table->increments('id');
			$table->timestamps();

			$table->string('code');
			$table->string('name');
			$table->json('fields');
		});
	}

	public function down()
	{
		Schema::dropIfExists('email_types');
	}
}
