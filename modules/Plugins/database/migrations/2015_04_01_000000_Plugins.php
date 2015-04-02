<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Plugins extends Migration
{
	public function up()
	{
		Schema::create('installed_plugins', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->json('settings');
		});
	}

	public function down()
	{
		Schema::dropIfExists('installed_plugins');
	}
}
