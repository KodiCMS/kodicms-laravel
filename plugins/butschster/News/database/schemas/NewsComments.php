<?php

use KodiCMS\Plugins\Loader\PluginSchema;
use Illuminate\Database\Schema\Blueprint;

class NewsComments extends PluginSchema
{
	/**
	 * @return string
	 */
	public function getTableName()
	{
		return 'news_comments';
	}

	public function up()
	{
		Schema::create($this->getTableName(), function (Blueprint $table) {
			$table->increments('id');
			$table->text('content');
			$table->integer('user_id')->index();
			$table->integer('news_id')->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists($this->getTableName());
	}
}