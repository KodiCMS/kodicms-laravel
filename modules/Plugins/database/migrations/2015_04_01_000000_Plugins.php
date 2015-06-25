<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Plugins extends Migration
{
	public function up()
	{
		Schema::create('installed_plugins', function (Blueprint $table) {
			$table->timestamps();

			$table->increments('id');
			$table->string('name');
			$table->string('path');
			$table->json('settings');
		});
	}

	public function down()
	{
		Schema::dropIfExists('installed_plugins');
	}
}
