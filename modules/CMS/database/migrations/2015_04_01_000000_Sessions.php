<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Sessions extends Migration
{
	public function up()
	{
		Schema::create('sessions', function (Blueprint $table) {
			$table->string('id')->unique();
			$table->text('payload');
			$table->integer('last_activity');
		});
	}

	public function down()
	{
		Schema::dropIfExists('sessions');
	}
}
