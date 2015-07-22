<?php namespace KodiCMS\Datasource\Contracts;

use KodiCMS\Datasource\FieldType;
use Illuminate\Validation\Validator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Builder;

interface FieldInterface
{
	/**
	 * @return integer
	 */
	public function getId();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return integer
	 */
	public function getPosition();

	/**
	 * @return FieldType
	 */
	public function getType();

	/**
	 * @return string
	 */
	public function getTypeTitle();

	/**
	 * @return string
	 */
	public function getKey();

	/**
	 * @return string
	 */
	public function getDBKey();

	/**
	 * @return mixed
	 */
	public function getDefaultValue();

	/**
	 * @return string
	 */
	public function getTablePrefix();

	/**
	 * @param mixed $value
	 * @return string
	 */
	public function convertValueToHTML($value);

	/**
	 * @param mixed $value
	 * @return string
	 */
	public function convertValueToSQL($value);

	/**
	 * @param mixed $value
	 * @return string
	 */
	public function convertValueToHeadline($value);

	/**
	 * @return bool
	 */
	public function isSystem();

	/**
	 * @return bool
	 */
	public function isRequire();

	/**
	 * @return bool
	 */
	public function isVisible();

	/**
	 * @return bool
	 */
	public function isSearchable();

	/**
	 * @return array
	 */
	public function defaultSettings();

	/**************************************************************************
	 * Events
	 **************************************************************************/
	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onSetDocumentAttribute(DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetDocumentAttribute(DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetFormAttributeValue(DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $document
	 * @param Validator $validator
	 * @param $value
	 */
	public function onValidateDocument(DocumentInterface $document, Validator $validator, $value);

	/**
	 * @param DocumentInterface $document
	 * @param $value
	 */
	public function onDocumentCreate(DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $oldDocument
	 * @param DocumentInterface $document
	 * @param $value
	 */
	public function onDocumentUpdate(DocumentInterface $oldDocument, DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $document
	 */
	public function onDocumentRemove(DocumentInterface $document);


	/**************************************************************************
	 * Database
	 **************************************************************************/
	/**
	 * @param Builder $query
	 */
	public function querySelectColumn(Builder $query);

	/**
	 * @param Builder $query
	 * @param string $dir
	 */
	public function queryOrderBy(Builder $query, $dir = 'asc');

	/**
	 * @param Builder $query
	 * @param string $condition
	 * @param string $value
	 */
	public function queryWhereCondition(Builder $query, $condition, $value);

	/**
	 * @param Blueprint $table
	 */
	public function setDatabaseFieldType(Blueprint $table);
}