<?php

namespace KodiCMS\Datasource\Fields\Primitive;

use Illuminate\Validation\Validator;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\FieldTypeDateInterface;

class Date extends Timestamp implements FieldTypeDateInterface
{
    /**
     * @var string
     */
    protected $dateFormat = 'Y-m-d';

    /**
     * @var bool
     */
    protected $isEditable = true;

    /**
     * @var bool
     */
    protected $changeableDatabaseField = false;

    /**
     * @return array
     */
    public function booleanSettings()
    {
        return ['set_current'];
    }

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return [
            'default_value' => '0000-00-00',
        ];
    }

    /**
     * @return array
     */
    public function isCurrentDateByDefault()
    {
        return (bool) $this->getSetting('set_current');
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        if ($this->isCurrentDateByDefault()) {
            return date($this->dateFormat);
        }

        return $this->getSetting('default_value');
    }

    /**
     * @return string
     */
    public function getDatabaseDefaultValue()
    {
        return $this->getSetting('default_value');
    }

    /**
     * @param DocumentInterface $document
     * @param Validator         $validator
     *
     * @return array
     */
    public function getValidationRules(DocumentInterface $document, Validator $validator)
    {
        $rules = parent::getValidationRules($document, $validator);

        $rules[] = 'date';

        return $rules;
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->date($this->getDBKey())->default($this->getDatabaseDefaultValue());
    }
}
