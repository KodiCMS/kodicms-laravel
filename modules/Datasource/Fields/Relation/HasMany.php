<?php

namespace KodiCMS\Datasource\Fields\Relation;

use KodiCMS\Datasource\Fields\Relation;
use Illuminate\Database\Eloquent\Builder;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Repository\FieldRepository;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use Illuminate\Database\Eloquent\Relations\HasMany as HasManyRelation;

class HasMany extends Relation
{
    /**
     * @var bool
     */
    protected $hasDatabaseColumn = false;

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value)
    {
        $documents = $document->getAttribute($this->getRelationName())->map(function ($doc) {
            return \HTML::link($doc->getEditLink(), $doc->getTitle(), ['class' => 'popup']);
        })->all();

        return ! empty($documents) ? implode(', ', $documents) : null;
    }

    /**
     * @param Builder           $query
     * @param DocumentInterface $document
     */
    public function querySelectColumn(Builder $query, DocumentInterface $document)
    {
        $query->with($this->getRelationName());
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     */
    public function onDocumentFill(DocumentInterface $document, $value)
    {
        if (! is_null($relatedField = $this->relatedField)) {
            $documents = $relatedField->getSection()->getEmptyDocument()->whereIn('id', $value)->get();

            $document->{$this->getRelationName()}()->saveMany($documents);
        }
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    public function getRelatedDocumentValues(DocumentInterface $document)
    {
        if (! is_null($relatedField = $this->relatedField)) {
            $section = $relatedField->getSection();

            return $this->getDocumentRelation($document, $section)
                ->get()
                ->lists($section->getDocumentTitleKey(), $section->GetDocumentPrimaryKey())
                ->all();
        }

        return [];
    }

    /**
     * @param DocumentInterface     $document
     * @param SectionInterface|null $relatedSection
     * @param FieldInterface|null   $relatedField
     *
     * @return HasManyRelation
     */
    public function getDocumentRelation(
        DocumentInterface $document, SectionInterface $relatedSection = null, FieldInterface $relatedField = null
    ) {
        $instance = $relatedSection->getEmptyDocument()->newQuery();

        $foreignKey = $this->relatedField->getDBKey();
        $localKey = $relatedSection->getDocumentPrimaryKey();

        return new HasManyRelation($instance, $document, $foreignKey, $localKey);
    }

    /**
     * @param FieldRepository $repository
     *
     * @throws \KodiCMS\Datasource\Exceptions\FieldException
     */
    public function onCreated(FieldRepository $repository)
    {
        if (! is_null($this->getRelatedFieldId())) {
            return;
        }

        $relatedField = $repository->create([
            'type'               => 'has_one',
            'section_id'         => $this->getRelatedSectionId(),
            'is_system'          => 1,
            'key'                => $this->getDBKey().'_has_many',
            'name'               => $this->getSection()->getName(),
            'related_section_id' => $this->getSection()->getId(),
            'related_field_id'   => $this->getId(),
        ]);

        if (! is_null($relatedField)) {
            $this->update(['related_field_id' => $relatedField->getId()]);
        }
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    protected function fetchDocumentTemplateValues(DocumentInterface $document)
    {
        return array_merge(parent::fetchDocumentTemplateValues($document), [
            'value' => $this->getRelatedDocumentValues($document),
        ]);
    }
}
