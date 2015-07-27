<?php namespace KodiCMS\Datasource\Contracts;

interface FieldTypeRelationInterface
{
	/**
	 * @return integer
	 */
	public function getRelatedSectionId();

	/**
	 * @param DocumentInterface $document
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function getDocumentRalation(DocumentInterface $document);

	/**
	 * @return string
	 */
	public function getRelatedDBKey();
}