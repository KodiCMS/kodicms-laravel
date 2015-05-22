<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class DatasourceFields extends Migration
{
	public function up()
	{
		Schema::create('datasource_fields', function (Blueprint $table) {
			$table->increments('id');

			$table->string('ds_id')->index();
			$table->boolean('is_system')->default(false);
			$table->string('key');
			$table->string('type');

			$table->string('name');
			$table->string('related_ds')->index();

			$table->json('settings');
			$table->integer('position')->default(0);

			$table->unique(['ds_id', 'key']);

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('datasource_fields');
	}
}
