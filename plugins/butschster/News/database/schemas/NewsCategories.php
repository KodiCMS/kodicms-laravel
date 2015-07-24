<?php

use KodiCMS\Plugins\Loader\PluginSchema;
use Illuminate\Database\Schema\Blueprint;

class NewsCategories extends PluginSchema
{
	/**
	 * @return string
	 */
	public function getTableName()
	{
		return 'news_categories';
	}

	public function up()
	{
		Schema::create($this->getTableName(), function (Blueprint $table) {
			$table->increments('id');
			$table->string('slug', 80)->unique();
			$table->string('name', 45)->unique();

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists($this->getTableName());
	}
}