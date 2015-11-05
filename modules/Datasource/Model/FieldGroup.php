<?php

namespace KodiCMS\Datasource\Model;

use FieldGroupManager;
use KodiCMS\Datasource\Fields\FieldsCollection;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\FieldGroupInterface;
use KodiCMS\Datasource\Contracts\FieldsCollectionInterface;

class FieldGroup extends DatasourceModel implements FieldGroupInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'datasource_field_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['section_id', 'type', 'position', 'name'];

    /**
     * @var FieldsCollectionInterface
     */
    protected $fields;

    /**
     * @var string
     */
    protected $template = 'datasource::document.group.default';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getUniqueId()
    {
        return time();
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = new FieldsCollection($fields);

        return $this;
    }

    /**
     * @return FieldsCollectionInterface
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function addField(FieldInterface $field)
    {
        $this->getFields()->add($field);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields()
    {
        return $this->hasMany(Field::class, 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * @param DocumentInterface $document
     *
     * @return string
     */
    public function renderDocumentTemplate(DocumentInterface $document)
    {
        if (count($this->getFields()) > 0) {
            return view($this->template, [
                'name'     => $this->getName(),
                'fields'   => $this->getFields(),
                'group'    => $this,
                'document' => $document,
            ])->render();
        }

        return;
    }

    /**
     * @return DatasourceManagerInterface
     */
    public static function getManagerClass()
    {
        return FieldGroupManager::getFacadeRoot();
    }
}
