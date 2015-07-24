<?php namespace KodiCMS\Datasource\Contracts;

use Illuminate\Validation\Validator;

interface DocumentInterface
{
	/**
	 * @return string
	 */
	public function getEditLink();

	/**
	 * @return string
	 */
	public function getCreateLink();

	/**
	 * @param  string $key
	 * @return mixed
	 */
	public function getFormValue($key);

	/**
	 * @param  string $key
	 * @return mixed
	 */
	public function getHeadlineValue($key);


	/**
	 * @return SectionInterface
	 */
	public function getSection();

	/**
	 * @return array
	 */
	public function getSectionFields();

	/**
	 * @return array
	 */
	public function getEditableFields();

	/**
	 * @param Validator $validator
	 *
	 * @return array
	 */
	public function getValidationRules(Validator $validator);

	/**
	 * @param integer|string $id
	 * @param array|null $fields
	 * @param string|integer|null $primaryKeyField
	 * @return DocumentInterface|null
	 */
	public function getDocumentById($id, array $fields = null, $primaryKeyField = null);

	/**
	 * @param bool|array|null $fields
	 * @param array $orderRules
	 * @param array $filterRules
	 * @return Collection
	 */
	public function getDocuments($fields = true, array $orderRules = [], array $filterRules = []);
}