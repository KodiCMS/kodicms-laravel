<?php

namespace KodiCMS\Datasource\Fields\Primitive;

use Illuminate\Validation\Validator;
use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Filter\Type\Number;
use KodiCMS\Datasource\Contracts\DocumentInterface;

class Integer extends Primitive
{
    /**
     * @return string
     */
    public function getFilterTypeClass()
    {
        return Number::class;
    }

    /**
     * @return array
     */
    public function booleanSettings()
    {
        return ['auto_increment', 'unique'];
    }

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return [
            'length'         => 10,
            'default'        => 0,
            'unique'         => false,
            'min'            => 0,
            'max'            => 1000000,
            'auto_increment' => false,
            'increment_step' => 1,
        ];
    }

    public function isAutoIncrementable()
    {
        return (bool) $this->getSetting('auto_increment');
    }

    /**
     * @return int|null
     */
    public function getLength()
    {
        return $this->getSetting('length');
    }

    /**
     * @return int|null
     */
    public function getMin()
    {
        return $this->getSetting('min');
    }

    /**
     * @return int|null
     */
    public function getMax()
    {
        return $this->getSetting('max');
    }

    /**
     * @return int|null
     */
    public function getIncrementStep()
    {
        return $this->getSetting('increment_step');
    }

    /**
     * @return string
     */
    public function getHeadlineType()
    {
        return 'num';
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
        $rules[] = 'numeric';
        $rules[] = "between:{$this->getMin()},{$this->getMax()}";

        return $rules;
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->unsignedInteger($this->getDBKey())->default($this->getDatabaseDefaultValue());
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onSetDocumentAttribute(DocumentInterface $document, $value)
    {
        return (int) $value;
    }

    /**************************************************************************
     * Setting mutators
     **************************************************************************/

    /**
     * @param int $defaultLength
     *
     * @return int
     */
    public function getSettingLength($defaultLength)
    {
        return (int) array_get($this->fieldSettings, 'length', $defaultLength);
    }

    /**
     * @param int $default
     *
     * @return int
     */
    public function getSettingMin($default)
    {
        return (int) array_get($this->fieldSettings, 'min', $default);
    }

    /**
     * @param int $value
     *
     * @return int
     */
    public function setSettingMin($value)
    {
        $this->fieldSettings['min'] = (int) $value;
    }

    /**
     * @param int $value
     *
     * @return int
     */
    public function setSettingMax($value)
    {
        $this->fieldSettings['max'] = (int) $value;
    }

    /**
     * @param int $default
     *
     * @return int
     */
    public function getSettingMax($default)
    {
        return (int) array_get($this->fieldSettings, 'max', $default);
    }

    /**
     * @param int $default
     *
     * @return int
     */
    public function getSettingIncrementStep($default)
    {
        $step = (int) array_get($this->fieldSettings, 'increment_step', $default);

        if ($step < 1) {
            $step = 1;
        }

        return $step;
    }

    /**
     * @param int $default
     *
     * @return int
     */
    public function getSettingDefaultValue($default = 0)
    {
        return (int) array_get($this->fieldSettings, 'default_value', $default);
    }

    /**
     * @return int
     */
    public function getNextIncrementedValue()
    {
        $value = \DB::table($this->getSection()->getSectionTableName())
            ->selectRaw("MAX(`{$this->getDBKey()}`) as max")
            ->value('max');

        return $value + $this->getIncrementStep();
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetFormValue(DocumentInterface $document, $value)
    {
        if (! $document->exists and $this->isAutoIncrementable()) {
            $value = $this->getNextIncrementedValue();
        }

        return parent::onGetFormValue($document, $value); // TODO: Change the autogenerated stub
    }
}
