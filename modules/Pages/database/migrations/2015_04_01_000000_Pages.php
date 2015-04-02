<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Pages extends Migration
{
	public function up()
	{
		Schema::create('pages', function (Blueprint $table) {
			$table->timestamps();
			$this->timestamp('published_at');

			$table->increments('id');
			$table->unsignedInteger('parent_id')
				->nullable()
				->index();

			$table->integer('status_id')->index()->default(100);

			$table->string('behavior')->nullable();

			$table->string('title');
			$table->string('slug', 100)->index();
			$table->string('breadcrumb', 100);

			$table->string('meta_title');
			$table->string('meta_keywords');
			$table->text('meta_description');

			$table->string('robots', 100);
			$table->string('layout_file');

			$table->unsignedInteger('created_by_id');
			$table->unsignedInteger('updated_by_id');

			$table->integer('position')->default(0);

			$table->boolean('use_redirect')->default(FALSE);
			$table->string('redirect_url')->nullable();
		});
	}

	public function down()
	{
		Schema::dropIfExists('pages');
	}
}
