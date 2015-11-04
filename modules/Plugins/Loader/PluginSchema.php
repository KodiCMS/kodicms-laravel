<?php

namespace KodiCMS\Plugins\Loader;

use Illuminate\Database\Migrations\Migration;

abstract class PluginSchema extends Migration
{
    /**
     * @return string
     */
    abstract public function getTableName();
}
