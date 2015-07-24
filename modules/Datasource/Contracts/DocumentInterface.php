<?php namespace KodiCMS\Datasource\Contracts;

interface DocumentInterface
{
	/**
	 * @return array
	 */
	public function getSectionFields();

	/**
	 * @return array
	 */
	public function getEditableFields();

	/**
	 * @param integer|string $id
	 * @param array|null $fields
	 * @param string|integer|null $primaryKeyField
	 * @return DocumentInterface|null
	 */
	public function getDocumentById($id, array $fields = null, $primaryKeyField = null);
}