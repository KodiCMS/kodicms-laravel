<?php

namespace KodiCMS\Datasource\Model;

use Illuminate\Validation\Validator;
use KodiCMS\Support\Traits\Tentacle;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Datasource\Fields\FieldsCollection;
use KodiCMS\Datasource\Observers\DocumentObserver;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Datasource\Contracts\FieldTypeDateInterface;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;
use KodiCMS\CMS\Http\Controllers\System\TemplateController;
use KodiCMS\Datasource\Contracts\FieldTypeRelationInterface;
use KodiCMS\Datasource\Contracts\FieldTypeOnlySystemInterface;

class Document extends Model implements DocumentInterface
{
    use Tentacle;

    const COND_EQ = 0;
    const COND_BTW = 1;
    const COND_GT = 2;
    const COND_LT = 3;
    const COND_GTEQ = 4;
    const COND_LTEQ = 5;
    const COND_CONTAINS = 6;
    const COND_LIKE = 7;
    const COND_NULL = 8;

    const FILTER_VALUE_PLAIN = 20;
    const FILTER_VALUE_GET = 40;
    const FILTER_VALUE_POST = 50;
    const FILTER_VALUE_BEHAVIOR = 30;

    protected static function boot()
    {
        parent::boot();
        static::observe(new DocumentObserver);
    }

    /**
     * @var SectionInterface
     */
    protected $section;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = null;

    /**
     * @var string
     */
    protected $editTemplate = 'datasource::document.edit';

    /**
     * @var string
     */
    protected $createTemplate = 'datasource::document.create';

    /**
     * @var string
     */
    protected $formTemplate = 'datasource::document.partials.form';

    /**
     * @param array                 $attributes
     * @param SectionInterface|null $section
     */
    public function __construct($attributes = [], SectionInterface $section = null)
    {
        if (! is_null($section)) {
            $this->section = $section;
            $this->table = $this->section->getSectionTableName();

            $this->primaryKey = $section->getDocumentPrimaryKey();
            if (! is_null($this->primaryKey)) {
                $this->incrementing = true;
            }

            foreach ($this->getSectionFields() as $field) {
                if ($field instanceof FieldTypeDateInterface) {
                    $this->dates[] = $field->getDBKey();
                }

                // TODO: подумать как это оптимизировать
                if ($field instanceof FieldTypeRelationInterface) {
                    $relatedSection = $field->relatedSection;
                    $relatedField = $field->relatedField;

                    $this->addRelation($field->getRelationName(), function () use (
                        $field, $relatedSection, $relatedField
                    ) {
                        return $field->getDocumentRelation($this, $relatedSection, $relatedField);
                    });
                }

                if (! ($field instanceof FieldTypeOnlySystemInterface) and $field->hasDatabaseColumn()) {
                    $this->fillable[] = $field->getDBKey();
                    $this->setAttribute($field->getDBKey(), $field->getDefaultValue());
                }
            }

            if ($this->getSectionFields()->offsetExists(static::CREATED_AT) and $this->getSectionFields()->offsetExists(static::UPDATED_AT)) {
                $this->timestamps = true;
            }
        }

        parent::__construct($attributes);
    }

    /**
     * @return string|int
     */
    public function getId()
    {
        return $this->getKey();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->{$this->section->getDocumentTitleKey()};
    }

    /**
     * @return string
     */
    public function getEditLink()
    {
        return route('backend.datasource.document.edit', [$this->section->getId(), $this->getKey()]);
    }

    /**
     * @return string
     */
    public function getCreateLink()
    {
        return route('backend.datasource.document.create', [$this->section->getId()]);
    }

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array $attributes
     *
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        parent::fill($attributes);

        foreach ($attributes as $key => $value) {
            if (! is_null($field = $this->getSectionFields()->getByKey($key))) {
                $field->onDocumentFill($this, $value);
            }
        }

        return $this;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function setAttribute($key, $value)
    {
        if (! is_null($field = $this->getSectionFields()->getByKey($key))) {
            $value = $field->onSetDocumentAttribute($this, $value);

            if (method_exists($field, 'setDocumentAttribute')) {
                return $field->setDocumentAttribute($this, $value);
            }
        }

        parent::setAttribute($key, $value);
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string $key
     *
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return $this->hasField($key);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasField($key)
    {
        return $this->getSectionFields()->offsetExists($key);
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->getSectionFields()->getByKey($key)->onGetDocumentValue($this, $value);
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function getFormValue($key)
    {
        $value = parent::getAttributeValue($key);

        if (! is_null($field = $this->getSectionFields()->getByKey($key))) {
            $value = $field->onGetFormValue($this, $value);
        }

        return $value;
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function getHeadlineValue($key)
    {
        $value = parent::getAttributeValue($key);

        if (! is_null($field = $this->getSectionFields()->getByKey($key))) {
            $value = $field->onGetHeadlineValue($this, $value);
        }

        return $value;
    }

    /**
     * @param SectionHeadlineInterface $headline
     *
     * @return array
     */
    public function toHeadlineArray(SectionHeadlineInterface $headline)
    {
        $fields = $headline->getHeadlineFields();

        $attributes = [
            0            => null,
            'primaryKey' => $this->getKey(),
        ];

        foreach ($fields as $key => $params) {
            if (array_get($params, 'type') == 'link') {
                $attributes[$key] = link_to($this->getEditLink(), $this->getHeadlineValue($key));
            } else {
                $attributes[$key] = $this->getHeadlineValue($key);
            }
        }

        return $attributes;
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string          $key
     * @param  WidgetInterface $widget
     *
     * @return mixed
     */
    public function getWidgetValue($key, WidgetInterface $widget)
    {
        $value = parent::getAttributeValue($key);
        if (! is_null($field = $this->getSectionFields()->getByKey($key))) {
            $value = $field->onGetWidgetValue($this, $widget, $value);
        }

        return $value;
    }

    /**
     * @return FieldsCollection
     */
    public function getSectionFields()
    {
        return $this->getSection()->getFields();
    }

    /**
     * @return array
     */
    public function getFieldsNames()
    {
        return $this->getSectionFields()->getNames();
    }

    /**
     * @return array
     */
    public function getEditableFields()
    {
        return $this->getSectionFields()->getEditable();
    }

    /**
     * @return SectionInterface
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return string
     */
    public function getEditTemplate()
    {
        return $this->editTemplate;
    }

    /**
     * @return string
     */
    public function getCreateTemplate()
    {
        return $this->createTemplate;
    }

    /**
     * @return string
     */
    public function getFormTemplate()
    {
        return $this->formTemplate;
    }

    /**
     * @param TemplateController $controller
     */
    public function onControllerLoad(TemplateController $controller)
    {
        foreach ($this->getSectionFields() as $field) {
            $field->onControllerLoad($this, $controller);
        }
    }

    /**
     * @param Validator $validator
     *
     * @return array
     */
    public function getValidationRules(Validator $validator)
    {
        $rules = [];

        foreach ($this->getEditableFields() as $field) {
            $rules[$field->getDBKey()] = $field->getValidationRules($this, $validator);
        }

        return $rules;
    }

    /**************************************************************************
     * Scopes
     **************************************************************************/

    /**
     * Scope a query to only include popular users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyPublished($query)
    {
        return $query->where('published', 1);
    }

    /**************************************************************************
     * Custom query builder by Headline/Widgets parameters
     **************************************************************************/

    /**
     * @param int|string      $id
     * @param array|null          $fields
     * @param string|int|null $primaryKeyField
     *
     * @return DocumentInterface|null
     */
    public function getDocumentById($id, array $fields = null, $primaryKeyField = null)
    {
        if (is_null($primaryKeyField)) {
            $primaryKeyField = $this->primaryKey;
        }

        $query = $this->buildQueryForWidget($fields);

        $result = $query->where($primaryKeyField, $id)->first();

        return is_null($result) ? new static([], $this->section) : $result;
    }

    /**
     * @param bool|array|null $fields
     * @param array           $orderRules
     * @param array           $filterRules
     *
     * @return Collection
     */
    public function getDocuments($fields = true, array $orderRules = [], array $filterRules = [])
    {
        return $this->buildQueryForWidget($fields, $orderRules, $filterRules);
    }

    /**
     * @param bool|array|null $fields
     * @param array           $orderRules
     * @param array           $filterRules
     *
     * @return Builder
     */
    protected function buildQueryForWidget($fields = true, array $orderRules = [], array $filterRules = [])
    {
        $query = $this->newQuery();

        $t = [$this->section->getId() => true];

        $selectFields = [];

        if (is_array($fields)) {
            foreach ($fields as $fieldKey) {
                if ($this->hasField($fieldKey)) {
                    continue;
                }

                $selectFields[] = $this->getSectionFields()->getByKey($fieldKey);
            }
        } elseif ($fields === true) {
            $selectFields = $this->getSectionFields();
        } elseif ($fields === false) {
            $query->selectRaw('COUNT(*) as total_docs');
        }

        // TODO: предусмотреть relation поля
        if ($fields !== false) {
            foreach ($selectFields as $field) {
                $field->querySelectColumn($query, $this);
            }
        }

        if (! empty($orderRules)) {
            $this->buildQueryOrdering($query, $orderRules, $t);
        }

        if (! empty($filterRules)) {
            $this->buildQueryFilters($query, $filterRules, $t);
        }

        return $query;
    }

    /**
     * @param DocumentQueryBuilder $query
     * @param array                $orderRules
     * @param array                $t
     */
    protected function buildQueryOrdering(DocumentQueryBuilder $query, array $orderRules, array &$t)
    {
        $j = 0;

        foreach ($orderRules as $rule) {
            $field = null;

            $fieldKey = key($rule);
            $dir = $rule[key($rule)];

            if (is_null($field = $this->getSectionFields()->getByKey($fieldKey))) {
                continue;
            }

            // TODO: предусмотреть relation поля
            $field->queryOrderBy($query, $dir);

            unset($field);

            $j++;
        }
    }

    /**************************************************************************
     * Override methods
     **************************************************************************/

    /**
     * Create a new instance of the given model.
     *
     * @param  array $attributes
     * @param  bool  $exists
     *
     * @return static
     */
    public function newInstance($attributes = [], $exists = false)
    {
        $model = new static($attributes, $this->section);
        $model->exists = $exists;

        return $model;
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new DocumentQueryBuilder($query, $this->section);
    }
}
