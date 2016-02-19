<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forms', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->string('textaddon')->nullable();
			$table->boolean('checkbox');
			$table->date('date')->nullable();
			$table->time('time')->nullable();
			$table->timestamp('timestamp')->nullable();
			$table->string('image')->nullable();
			$table->text('images')->nullable();
			$table->integer('select')->nullable();
			$table->timestamp('custom')->nullable();
			$table->text('textarea')->nullable();
			$table->text('ckeditor')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('forms');
	}

}
