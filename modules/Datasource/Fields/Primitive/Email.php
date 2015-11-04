<?php

namespace KodiCMS\Datasource\Fields\Primitive;

use Illuminate\Html\HtmlFacade;
use Illuminate\Validation\Validator;
use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\DocumentInterface;

class Email extends Primitive
{
    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value)
    {
        return empty($value) ? null : HtmlFacade::mailto($value);
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
        $rules[] = 'email';

        return $rules;
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->string($this->getDBKey(), 50);
    }
}
