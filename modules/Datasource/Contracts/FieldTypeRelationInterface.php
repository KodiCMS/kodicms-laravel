<?php namespace KodiCMS\Datasource\Contracts;

interface FieldTypeRelationInterface
{
	/**
	 * @return integer
	 */
	public function getRelatedSectionId();

	/**
	 * @param DocumentInterface $document
	 * @param SectionInterface $relatedSection
	 * @return BelongsToRelation
	 */
	public function getDocumentRelation(DocumentInterface $document, SectionInterface $relatedSection);

	/**
	 * @return string
	 */
	public function getRelatedDBKey();
}