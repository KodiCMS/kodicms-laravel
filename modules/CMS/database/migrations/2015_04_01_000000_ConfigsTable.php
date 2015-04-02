<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ConfigsTable extends Migration
{
	public function up()
	{
		Schema::create('config', function (Blueprint $table) {
			$table->string('group_name', 128);
			$table->string('config_key', 128);
			$table->json('config_value');

			$table->primary(['group_name', 'config_key']);
		});
	}

	public function down()
	{
		Schema::dropIfExists('config');
	}
}
