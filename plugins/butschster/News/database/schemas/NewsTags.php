<?php

use KodiCMS\Plugins\Loader\PluginSchema;
use Illuminate\Database\Schema\Blueprint;

class NewsTags extends PluginSchema
{
	/**
	 * @return string
	 */
	public function getTableName()
	{
		return 'news_tags';
	}

	public function up()
	{
		Schema::create($this->getTableName(), function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 45)->unique();
			$table->timestamps();
		});

		Schema::create('news_have_tags', function($table)
		{
			$table->increments('id');
			$table->integer('post_id')->unsigned();
			$table->integer('tag_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::dropIfExists($this->getTableName());
		Schema::dropIfExists('news_have_tags');
	}
}