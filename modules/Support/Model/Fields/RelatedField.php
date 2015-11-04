<?php

namespace KodiCMS\Support\Model\Fields;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class RelatedField extends SelectField
{
    /**
     * @var string
     */
    protected $keyField = 'id';

    /**
     * @var string
     */
    protected $valueField = 'name';

    /**
     * @var bool
     */
    protected $isMultiple = false;

    /**
     * @var Relation
     */
    protected $relatedModel;

    /**
     * @return Relation
     */
    public function getRelatedModel()
    {
        if (! isset($this->relatedModel)) {
            $this->relatedModel = $this->model->{$this->getModelKey()}();
        }

        return $this->relatedModel;
    }

    /**
     * @param sreing|null $prefix
     *
     * @return string
     */
    public function getName($prefix = null)
    {
        $name = parent::getName($prefix);

        return $this->isMultiple ? $name.'[]' : $name;
    }

    public function beforeRender()
    {
        if (($this->getRelatedModel() instanceof BelongsToMany) or ($this->getRelatedModel() instanceof HasMany) or ($this->getRelatedModel() instanceof HasManyThrough)) {
            $this->setAttributes(['multiple']);
            $this->isMultiple = true;
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function getOptions($key, $value)
    {
        $return = parent::getOptions($key, $value);

        if (empty($return) and ! is_null($value)) {
            $return = $this->getRelatedModel()->getRelated()->lists($this->valueField, $this->keyField)->all();
        }

        return $return;
    }
}
