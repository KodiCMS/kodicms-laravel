<?php

namespace KodiCMS\Datasource\Fields\Primitive;

use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;

class Slug extends Primitive
{
    /**
     * @var bool
     */
    protected $canBeUsedAsDocumentID = true;

    /**
     * @return array
     */
    public function booleanSettings()
    {
        return ['from_document_title', 'is_unique'];
    }

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return [
            'separator'           => '-',
            'from_document_title' => false,
            'is_unique'           => true,
            'is_required'         => true,
        ];
    }

    /**
     * @return bool
     */
    public function fromDocumentTitle()
    {
        return (bool) $this->getSetting('from_document_title');
    }

    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->getSetting('separator');
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->string($this->getDBKey(), 255);
    }
}
