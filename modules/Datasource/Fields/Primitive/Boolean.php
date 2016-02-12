<?php

namespace KodiCMS\Datasource\Fields\Primitive;

use UI;
use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Filter\Type\Checkbox;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;

class Boolean extends Primitive
{
    const STYLE_RADIO = 0;
    const STYLE_CHECKBOX = 1;
    const STYLE_SELECT = 2;

    /**
     * @return string
     */
    public function getFilterTypeClass()
    {
        return Checkbox::class;
    }

    /**
     * @var bool
     */
    protected $changeableDatabaseField = false;

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return [
            'style'         => static::STYLE_CHECKBOX,
            'default_value' => false,
        ];
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value)
    {
        return (bool) $value ? UI::icon('check') : UI::icon('close');
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->boolean($this->getDBKey());
    }

    /**
     * @param SectionHeadlineInterface $headline
     *
     * @return array
     */
    public function getHeadlineParameters(SectionHeadlineInterface $headline)
    {
        $params = parent::getHeadlineParameters($headline);
        $params['class'] = 'text-center';

        return $params;
    }

    /**
     * @return int
     */
    public function getDisplayStyle()
    {
        return $this->getSetting('style', static::STYLE_CHECKBOX);
    }

    /**
     * TODO: translate.
     * @return array
     */
    public function getDisplayStyles()
    {
        return [
            static::STYLE_RADIO    => 'Radio buttons',
            static::STYLE_CHECKBOX => 'Checkbox',
            static::STYLE_SELECT   => 'Dropdown',
        ];
    }
}
