<?php

namespace KodiCMS\Datasource\Fields\Primitive;

use Illuminate\Database\Schema\Blueprint;

class DateTime extends Date
{
    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->dateTime($this->getDBKey())->default($this->getDefaultValue());
    }
}
