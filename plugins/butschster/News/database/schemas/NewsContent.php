<?php

use KodiCMS\Plugins\Loader\PluginSchema;
use Illuminate\Database\Schema\Blueprint;

class NewsContent extends PluginSchema
{
    /**
     * @return string
     */
    public function getTableName()
    {
        return 'news_content';
    }

    public function up()
    {
        Schema::create($this->getTableName(), function (Blueprint $table) {
            $table->increments('news_id');
            $table->string('description');
            $table->string('description_filtered');

            $table->string('content');
            $table->string('content_filtered');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->getTableName());
    }
}
