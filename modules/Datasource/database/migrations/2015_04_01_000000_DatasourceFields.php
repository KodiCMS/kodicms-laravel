<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatasourceFields extends Migration
{
	public function up()
	{
		Schema::create('datasource_fields', function (Blueprint $table)
		{
			$table->increments('id');

			$table->integer('section_id')->index();
			$table->boolean('is_system')->default(false);

			$table->string('key');
			$table->string('type');

			$table->string('name');
			$table->integer('related_section_id')->index();
			$table->integer('related_field_id');
			$table->string('related_table');

			$table->json('settings');
			$table->integer('position')->default(0);

			$table->unique(['section_id', 'key']);

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('datasource_fields');
	}
}
