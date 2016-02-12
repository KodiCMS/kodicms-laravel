<?php

namespace KodiCMS\Datasource\Model;

use DB;
use FieldManager;
use KodiCMS\Datasource\FieldType;
use KodiCMS\Datasource\Filter\Type;
use Illuminate\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Contracts\Support\Arrayable;
use KodiCMS\Datasource\Observers\FieldObserver;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Exceptions\FieldException;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\FilterTypeInterface;
use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;
use KodiCMS\CMS\Http\Controllers\System\TemplateController;

class Field extends DatasourceModel implements FieldInterface, Arrayable
{
    protected static function boot()
    {
        parent::boot();
        static::observe(new FieldObserver());
    }

    /**
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * @var array
     */
    protected $fieldSettings = [];

    /**
     * @var string
     */
    protected $table = 'datasource_fields';

    /**
     * @var FieldType
     */
    protected $fieldType;

    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * @var bool
     */
    protected $isEditable = true;

    /**
     * @var bool
     */
    protected $isOrderable = true;

    /**
     * @var bool
     */
    protected $canBeUsedAsDocumentID = false;

    /**
     * @var bool
     */
    protected $hasDatabaseColumn = true;

    /**
     * @var bool
     */
    protected $changeableDatabaseField = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'section_id',
        'key',
        'type',
        'name',
        'related_section_id',
        'related_field_id',
        'related_table',
        'position',
        'settings',
        'is_system',
        'froup_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'section_id'         => 'integer',
        'froup_id'           => 'integer',
        'key'                => 'string',
        'type'               => 'string',
        'name'               => 'string',
        'related_section_id' => 'integer',
        'is_system'          => 'boolean',
        'is_editable'        => 'boolean',
        'position'           => 'integer',
        'settings'           => 'array',
    ];

    /**
     * @var string
     */
    protected $filterTypeClass;

    /**
     * @var FilterTypeInterface
     */
    protected $filterType;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @return FilterTypeInterface
     */
    public function getFilterType()
    {
        if (is_null($this->filterType)) {
            $this->initFilterType();
        }

        return $this->filterType;
    }

    /**
     * @return string
     */
    public function getFilterTypeClass()
    {
        return Type\String::class;
    }

    /**
     * @return void
     */
    public function initFilterType()
    {
        if (! class_exists($type = $this->getFilterTypeClass())) {
            $type = Type::class;
        }

        $this->filterType = new $type($this);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * @param string $tablePrefix
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
    }

    /**
     * @return string
     */
    public function getDBKey()
    {
        return $this->getTablePrefix().$this->getKey();
    }

    /**
     * @return SectionInterface
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return bool
     */
    public function isAttachedToSection()
    {
        return ! is_null($this->section_id);
    }

    /**
     * @return bool
     */
    public function isSystem()
    {
        return (bool) $this->is_system;
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        return (bool) $this->getSetting('is_editable', $this->isEditable);
    }

    /**
     * @return bool
     */
    public function isConfigurable()
    {
        return $this->getSetting('is_configurable', true);
    }

    /**
     * @return bool
     */
    public function isOrderable()
    {
        return $this->isOrderable;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return (bool) $this->getSetting('is_required');
    }

    /**
     * @return bool
     */
    public function isUnique()
    {
        return (bool) $this->getSetting('is_unique');
    }

    /**
     * @return bool
     */
    public function canBeUsedAsDocumentID()
    {
        return (bool) $this->canBeUsedAsDocumentID;
    }

    /**
     * @return bool
     */
    public function hasDatabaseColumn()
    {
        return $this->hasDatabaseColumn;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->getSetting('headline_parameters.visible');
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return $this->getSetting('searchable', false);
    }

    /**
     * @return string
     */
    public function getDatabaseDefaultValue()
    {
        return $this->getDefaultValue();
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->getSetting('default_value');
    }

    /**
     * @return mixed
     */
    public function getHint()
    {
        return $this->getSetting('hint');
    }

    /**
     * @return int
     */
    public function getRelatedSectionId()
    {
        return (int) $this->related_section_id;
    }

    /**
     * @return int
     */
    public function getRelatedFieldId()
    {
        return $this->related_field_id;
    }

    /**
     * @return string
     */
    public function getRelatedTable()
    {
        return $this->related_table;
    }

    /**
     * @param SectionHeadlineInterface $headline
     *
     * @return array
     */
    public function getHeadlineParameters(SectionHeadlineInterface $headline)
    {
        return array_merge($this->getSetting('headline_parameters', []), [
            'id'         => $this->getId(),
            'name'       => $this->getName(),
            'orderable'  => $this->isOrderable() ? 'true' : 'false',
            'searchable' => $this->isSearchable() ? 'true' : 'false',
            'type'       => $this->getHeadlineType(),
        ]);
    }

    /**
     * @return string
     */
    public function getHeadlineType()
    {
        return 'string';
    }

    /**************************************************************************
     * Type
     **************************************************************************/

    /**
     * @return \KodiCMS\Datasource\FieldType|null
     * @throws FieldException
     */
    public function getType()
    {
        if ($this->fieldType) {
            return $this->fieldType;
        }

        if (is_null($typeObject = FieldManager::getFieldTypeBy('type', $this->type))) {
            throw new FieldException(
                "Datasource field type {$this->type} not found"
            );
        }

        return $this->fieldType = $typeObject;
    }

    /**
     * @return string
     */
    public function getTypeTitle()
    {
        return $this->getType()->getTitle();
    }

    /**
     * @param bool $status
     */
    public function setVisibleStatus($status)
    {
        $this->setSetting(['headline_parameters' => ['visible' => (bool) $status]]);
    }

    /**
     * @param array $params
     */
    public function setSettingHeadlineParameters($params)
    {
        $headlineParams = array_get($this->fieldSettings, 'headline_parameters', []);
        $this->fieldSettings['headline_parameters'] = array_merge($headlineParams, $params);
    }

    /**
     * @param DocumentInterface $document
     * @param Validator         $validator
     *
     * @return array
     */
    public function getValidationRules(DocumentInterface $document, Validator $validator)
    {
        $rules = [];

        if ($this->isRequired()) {
            $rules[] = 'required';
        }

        if ($this->isUnique()) {
            $table = $this->getSection()->getSectionTableName();

            if (is_null($uniqueRule = $this->getSetting('unique_rule'))) {
                $uniqueRule = 'unique::table,:field,:id,:id_field';
            }

            $replace = [
                ':table'    => $table,
                ':field'    => $this->getDBKey(),
                ':id'       => $document->exists ? $document->getId() : 'NULL',
                ':id_field' => $document->getKeyName(),
            ];

            foreach ($validator->getData() as $field => $value) {
                $replace['@'.$field] = is_array($value) ? implode(',', $value) : $value;
            }

            $uniqueRule = strtr($uniqueRule, $replace);

            $uniqueRule = preg_replace('/(\,\@[a-z_-]+)/', ',NULL', $uniqueRule);

            $rules[] = $uniqueRule;
        }

        if (! is_null($customRules = $this->getSetting('validation_rules'))) {
            $rules += explode('|', $customRules);
        }

        return $rules;
    }

    /**************************************************************************
     * Events
     **************************************************************************/

    /**
     * @param Blueprint $table
     */
    public function onDatabaseCreate(Blueprint $table)
    {
    }

    /**
     * @param Blueprint $table
     */
    public function onDatabaseUpdate(Blueprint $table)
    {
    }

    /**
     * @param Blueprint $table
     */
    public function onDatabaseDrop(Blueprint $table)
    {
        $table->dropColumn($this->getDBKey());
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onSetDocumentAttribute(DocumentInterface $document, $value)
    {
        return $value;
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetDocumentValue(DocumentInterface $document, $value)
    {
        return $value;
    }

    /**
     * @param DocumentInterface $document
     * @param WidgetInterface   $widget
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetWidgetValue(DocumentInterface $document, WidgetInterface $widget, $value)
    {
        return $value;
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetFormValue(DocumentInterface $document, $value)
    {
        return $value;
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value)
    {
        return $value;
    }

    /**
     * @param DocumentInterface $document
     * @param                   $value
     */
    public function onDocumentCreating(DocumentInterface $document, $value)
    {
    }

    /**
     * @param DocumentInterface $document
     * @param                   $value
     */
    public function onDocumentCreated(DocumentInterface $document, $value)
    {
    }

    /**
     * @param DocumentInterface $document
     * @param                   $value
     */
    public function onDocumentUpdating(DocumentInterface $document, $value)
    {
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     */
    public function onDocumentFill(DocumentInterface $document, $value)
    {
    }

    /**
     * @param DocumentInterface $document
     */
    public function onDocumentDeleting(DocumentInterface $document)
    {
    }

    /**
     * @param DocumentInterface  $document
     * @param TemplateController $controller
     */
    public function onControllerLoad(DocumentInterface $document, TemplateController $controller)
    {
        $controller->includeModuleMediaFile('fields/'.$this->getType()->getType());
    }

    /**************************************************************************
     * Database
     **************************************************************************/

    /**
     * @param Builder           $query
     * @param DocumentInterface $document
     */
    public function querySelectColumn(Builder $query, DocumentInterface $document)
    {
        $query->addSelect($this->getDBKey());
    }

    /**
     * @param Builder $query
     * @param string  $dir
     */
    public function queryOrderBy(Builder $query, $dir = 'asc')
    {
        $query->orderBy($this->getDBKey(), $dir);
    }

    /**
     * @return bool
     */
    public function isChangeableDatabaseField()
    {
        return $this->changeableDatabaseField;
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->string($this->getDBKey(), $this->getSetting('length', 255))->nullable();
    }

    /**************************************************************************
     * Relations
     **************************************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(\KodiCMS\Datasource\Model\Section::class, 'section_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relatedSection()
    {
        return $this->belongsTo(\KodiCMS\Datasource\Model\Section::class, 'related_section_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relatedField()
    {
        return $this->belongsTo(\KodiCMS\Datasource\Model\Field::class, 'related_field_id');
    }

    /**************************************************************************
     * Other
     **************************************************************************/

    /**
     * @return string
     */
    protected function getSettingsProperty()
    {
        return 'fieldSettings';
    }

    /**
     * @return float|int
     */
    public function getLastPosition()
    {
        return DB::table($this->getTable())->where('section_id', $this->section_id)->max('position');
    }

    /**
     * @return DatasourceManagerInterface
     */
    public static function getManagerClass()
    {
        return FieldManager::getFacadeRoot();
    }

    /**************************************************************************
     * Render
     **************************************************************************/

    /**
     * @param DocumentInterface $document
     *
     * @return string
     */
    public function renderDocumentTemplate(DocumentInterface $document, $template = null)
    {
        if (is_null($template)) {
            $template = $this->getType()->getDocumentTemplate();
        }

        return view($template, array_merge($this->toArray(), $this->fetchDocumentTemplateValues($document)))->render();
    }

    /**
     * @param WidgetInterface $widget
     *
     * @return string
     */
    public function renderWidgetFieldTemplate(WidgetInterface $widget)
    {
        return view($this->getType()->getWidgetTemplate(), [
            'widget' => $widget,
            'field'  => $this,
        ])->render();
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    protected function fetchDocumentTemplateValues(DocumentInterface $document)
    {
        return [
            'value'    => $document->getFormValue($this->getDBKey()),
            'document' => $document,
            'section'  => $document->getSection(),
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'    => $this->getId(),
            'key'   => $this->getDBKey(),
            'name'  => $this->getName(),
            'hint'  => $this->getHint(),
            'field' => $this,
        ];
    }

    public function __toString()
    {
        return $this->renderDocumentTemplate();
    }

    /**************************************************************************
     * Widgets
     **************************************************************************/

    public function getWidgetTypes()
    {
        return [];
    }
}
