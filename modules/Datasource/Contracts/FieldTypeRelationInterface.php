<?php namespace KodiCMS\Datasource\Contracts;

use Illuminate\Database\Eloquent\Relations\Relation;

interface FieldTypeRelationInterface
{
	/**
	 * @return integer
	 */
	public function getRelatedSectionId();

	/**
	 * @param DocumentInterface $document
	 * @param SectionInterface $relatedSection
	 * @param FieldInterface|null $relatedField
	 *
	 * @return Relation
	 */
	public function getDocumentRelation(DocumentInterface $document, SectionInterface $relatedSection, FieldInterface $relatedField = null);

	/**
	 * @return string
	 */
	public function getRelatedDBKey();
}