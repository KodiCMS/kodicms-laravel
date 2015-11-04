<?php

namespace KodiCMS\Datasource\Fields;

use DatasourceManager;
use KodiCMS\Datasource\Model\Field;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Datasource\Contracts\FieldTypeRelationInterface;

abstract class Relation extends Field implements FieldTypeRelationInterface
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['relatedSection'];

    /**
     * @var bool
     */
    protected $isOrderable = false;

    /**
     * @return array
     */
    public function getSectionList()
    {
        return DatasourceManager::getSectionsFormHTML();
    }

    /**
     * @return int
     */
    public function getRelatedSectionId()
    {
        if ($this->related_section_id === null) {
            $this->related_section_id = $this->section_id;
        }

        return $this->related_section_id;
    }

    /**
     * @return string
     */
    public function getRelatedDBKey()
    {
        return $this->getDBKey().'_related_'.$this->getId();
    }

    /**
     * @return string
     */
    public function getRelationName()
    {
        return camel_case($this->getDBKey().'_relation');
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    protected function fetchDocumentTemplateValues(DocumentInterface $document)
    {
        $relatedSection = $this->relatedSection;

        return array_merge(parent::fetchDocumentTemplateValues($document), [
            'relatedDocument' => $this->getDocumentRelation($document, $relatedSection)->first(),
            'relatedSection'  => $relatedSection,
            'relatedField'    => $this->relatedField,
        ]);
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
        return ! is_null($related = $document->getAttribute($this->getRelationName())) ? $related->toArray() : $value;
    }

    /**
     * @param DocumentInterface $document
     */
    public function onRelatedDocumentDeleting(DocumentInterface $document)
    {
    }
}
