<?php

namespace KodiCMS\Datasource\Widget;

use Request;
use Frontpage;
use KodiCMS\Widgets\Widget\Decorator;
use KodiCMS\Widgets\Traits\WidgetCache;
use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Datasource\Traits\WidgetDatasource;
use KodiCMS\Datasource\Fields\Primitive\String;
use KodiCMS\Datasource\Fields\Primitive\Textarea;
use KodiCMS\Datasource\Traits\WidgetDatasourceFields;

class DatasourceDocument extends Decorator implements WidgetCacheable
{
    use WidgetCache, WidgetDatasource, WidgetDatasourceFields;

    const SOURCE_REQUEST = 'request';
    const SOURCE_BEHAVOUR = 'behaviour';

    /**
     * @var mixed
     */
    protected $documentId = null;

    /**
     * @var array
     */
    protected static $cachedDocuments = [];

    /**
     * @var string
     */
    protected $settingsTemplate = 'datasource::widgets.document.settings';

    /**
     * @return array
     */
    public function booleanSettings()
    {
        return ['throw_404'];
    }

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return [
            'document_id_source'     => static::SOURCE_REQUEST,
            'document_id_source_key' => 'id',
        ];
    }

    public function onLoad()
    {
        $document = $this->getDocument();

        if (! $document->exists and $this->getSetting('throw_404')) {
            abort(404);
        }

        Frontpage::setMetaParams('document_title', $document->getTitle(), 'title');
        foreach (['title', 'keywords', 'description'] as $metaKey) {
            if (! is_null($setting = $this->getSetting('meta_'.$metaKey))) {
                Frontpage::setMetaParams(
                    'document_meta_'.$metaKey,
                    $document->getAttribute($setting),
                    'meta_'.$metaKey
                );
            }
        }
    }

    /**
     * @param mixed $id
     */
    public function setDocumentId($id)
    {
        $this->documentId = $id;
    }

    /**
     * @return array
     */
    public function getFieldsSource()
    {
        return [
            static::SOURCE_REQUEST  => 'Request',
            static::SOURCE_BEHAVOUR => 'Behaviour',
        ];
    }

    /**
     * @return array
     */
    public function getIdFields()
    {
        $fieldObjects = ! $this->getSection() ? [] : $this->section->getFields();
        $fields = [];
        foreach ($fieldObjects as $field) {
            if ($field->canBeUsedAsDocumentID()) {
                $fields[$field->getDBKey()] = $field->getName();
            }
        }

        return $fields;
    }

    /**
     * @return array
     */
    public function getMetaFields()
    {
        $fieldObjects = ! $this->getSection() ? [] : $this->section->getFields();
        $fields = [];
        foreach ($fieldObjects as $field) {
            if (($field instanceof String) or ($field instanceof Textarea)) {
                $fields[$field->getDBKey()] = $field->getName();
            }
        }

        return ['--------'] + $fields;
    }

    /**
     * @return array
     */
    public function prepareSettingsData()
    {
        $fields = ! $this->getSection() ? [] : $this->section->getFields();

        return compact('fields');
    }

    /**
     * @return string|null
     */
    public function getDocumentId()
    {
        if (! is_null($this->documentId)) {
            return $this->documentId;
        }

        if (! is_null($key = $this->getSetting('document_id_source_key'))) {
            switch ($this->document_id_source) {
                case static::SOURCE_BEHAVOUR:
                    if (! is_null($behaviour = Frontpage::getBehaviorObject())) {
                        return $behaviour->getRouter()->getParameter($key);
                    }

                    return;
                default:
                    return Request::get($key);
            }
        }

        return;
    }

    /**
     * @return array [[Document] $rawDocument, [array] $document, [KodiCMS\Datasource\Contracts\SectionInterface]
     *               $section]
     */
    public function prepareData()
    {
        $visibleFields = [];

        foreach ($this->getSection()->getFields() as $field) {
            if (in_array($field->getDBKey(), $this->getSelectedFields())) {
                $visibleFields[] = $field;
            }
        }

        $rawDocument = $this->getDocument();
        $document = [];
        foreach ($visibleFields as $field) {
            $document[$field->getDBKey()] = $rawDocument->getWidgetValue($field->getDBKey(), $this);
        }

        return [
            'rawDocument' => $rawDocument,
            'document'    => $document,
            'section'     => $this->getSection(),
        ];
    }

    /**
     * @param int|null $id
     * @param int      $recurse
     *
     * @return array
     */
    public function getDocument($id = null, $recurse = 3)
    {
        $document = $this->getSection()->getEmptyDocument();

        if ($id === null) {
            $id = $this->getDocumentId();
        }

        if (empty($id)) {
            return $document;
        }

        if (isset(static::$cachedDocuments[$id])) {
            return static::$cachedDocuments[$id];
        }

        $document = $document->getDocumentById($id, $this->getSelectedFields(), $this->document_id);

        return static::$cachedDocuments[$id] = $document;
    }
}
