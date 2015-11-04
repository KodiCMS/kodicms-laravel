<?php

namespace KodiCMS\Datasource\Fields\Primitive;

use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;

class String extends Primitive
{
    /**
     * @return array
     */
    public function booleanSettings()
    {
        return ['use_filemanager'];
    }

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return [
            'length' => 255,
        ];
    }

    /**
     * @return array
     */
    public function isUseFilemanager()
    {
        return (bool) $this->getSetting('use_filemanager');
    }

    public function getLength()
    {
        return $this->getSetting('length');
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->string($this->getDBKey(), $this->getLength());
    }

    /**************************************************************************
     * Setting mutators
     **************************************************************************/

    /**
     * @param int $defaultLength
     *
     * @return int
     */
    public function getSettingLength($defaultLength = 255)
    {
        return (int) array_get($this->fieldSettings, 'length', $defaultLength);
    }
}
