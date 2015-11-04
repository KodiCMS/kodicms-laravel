<?php

namespace KodiCMS\Datasource\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;

interface FieldTypeRelationInterface
{
    /**
     * @param DocumentInterface $document
     * @param SectionInterface $relatedSection
     * @param FieldInterface|null $relatedField
     *
     * @return Relation
     */
    public function getDocumentRelation(
        DocumentInterface $document, SectionInterface $relatedSection = null, FieldInterface $relatedField = null
    );

    /**
     * @param DocumentInterface $document
     */
    public function onRelatedDocumentDeleting(DocumentInterface $document);

    /**
     * @return string
     */
    public function getRelationName();
}
