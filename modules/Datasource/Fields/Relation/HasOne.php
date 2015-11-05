<?php

namespace KodiCMS\Datasource\Fields\Relation;

use KodiCMS\Datasource\Fields\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Builder;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Repository\FieldRepository;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use Illuminate\Database\Eloquent\Relations\HasOne as HasOneRelation;

class HasOne extends Relation
{
    const ONE_TO_ONE = 'one_to_one';
    const ONE_TO_MANY = 'one_to_many';

    /**
     * @param DocumentInterface     $document
     * @param SectionInterface|null $relatedSection
     * @param FieldInterface|null   $relatedField
     *
     * @return HasOneRelation
     */
    public function getDocumentRelation(
        DocumentInterface $document, SectionInterface $relatedSection = null, FieldInterface $relatedField = null
    ) {
        $instance = $relatedSection->getEmptyDocument()->newQuery();

        $foreignKey = $relatedSection->getSectionTableName().'.'.$relatedSection->getDocumentPrimaryKey();
        $otherKey = $this->getDBKey();
        $relation = $this->getRelationName();

        return new HasOneRelation($instance, $document, $foreignKey, $otherKey, $relation);
    }

    /**
     * @return array
     */
    public function getRelationTypes()
    {
        return [
            static::ONE_TO_ONE  => trans('datasource::fields.has_one.one_to_one'),
            static::ONE_TO_MANY => trans('datasource::fields.has_one.one_to_many'),
        ];
    }

    /**
     * @return array
     */
    public function getRelationType()
    {
        return $this->getSetting('relation_type', static::ONE_TO_ONE);
    }

    /**************************************************************************
     * Database
     **************************************************************************/

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->integer($this->getDBKey())->nullable();
    }

    /**
     * @param Builder           $query
     * @param DocumentInterface $document
     */
    public function querySelectColumn(Builder $query, DocumentInterface $document)
    {
        $query->addSelect($this->getDBKey())->with($this->getRelationName());
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    public function getRelatedDocumentValue(DocumentInterface $document)
    {
        $section = $this->relatedSection()->first();

        return \DB::table($section->getSectionTableName())
            ->addSelect($section->getDocumentPrimaryKey())
            ->addSelect($section->getDocumentTitleKey())
            ->where($section->getDocumentPrimaryKey(), $document->getAttribute($this->getDBKey()))
            ->lists($section->getDocumentTitleKey(), $section->getDocumentPrimaryKey());
    }

    /**************************************************************************
     * Events
     **************************************************************************/

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value)
    {
        return ! is_null($relatedDocument = $document->getAttribute($this->getRelationName()))
            ? \HTML::link($relatedDocument->getEditLink(), $relatedDocument->getTitle(), ['class' => 'popup'])
            : null;
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

        $data = [
            'section_id'         => $this->getRelatedSectionId(),
            'is_system'          => 1,
            'name'               => $this->getSection()->getName(),
            'related_section_id' => $this->getSection()->getId(),
            'related_field_id'   => $this->getId(),
        ];

        if ($this->getRelatedSectionId() == $this->section_id) {
            $data['settings']['is_editable'] = false;
        }

        $relatedField = null;

        switch ($this->getRelationType()) {
            case static::ONE_TO_ONE:
                $relatedField = $repository->create(array_merge([
                    'type' => 'belongs_to',
                    'key'  => $this->getDBKey().'_belongs_to',
                ], $data));

                break;
            case static::ONE_TO_MANY:
                $relatedField = $repository->create(array_merge([
                    'type' => 'has_many',
                    'key'  => $this->getDBKey().'_has_many',
                ], $data));

                break;
        }

        if (! is_null($relatedField)) {
            $this->update(['related_field_id' => $relatedField->getId()]);
        }
    }

    /**
     * @param FieldRepository $repository
     */
    public function onDeleted(FieldRepository $repository)
    {
        $this->relatedField->delete();
    }
}
