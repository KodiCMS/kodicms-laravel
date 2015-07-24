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
	public function getDatabaseDefaultValue();

	/**
	 * @param DocumentInterface $document
	 * @param Validator $validator
	 *
	 * @return array
	 */
	public function getValidationRules(DocumentInterface $document, Validator $validator);

	/**
	 * @param SectionHeadlineInterface $headline
	 *
	 * @return array
	 */
	public function getHeadlineParameters(SectionHeadlineInterface $headline);

	/**
	 * @return string
	 */
	public function getTablePrefix();

	/**
	 * @return bool
	 */
	public function isAttachedToSection();

	/**
	 * @return SectionInterface
	 */
	public function getSection();
	/**
	 * @return bool
	 */
	public function isSystem();

	/**
	 * @return bool
	 */
	public function isEditable();

	/**
	 * @return bool
	 */
	public function isRequired();

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
	public function onGetDocumentValue(DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetFormValue(DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetHeadlineValue(DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $document
	 * @param $value
	 */
	public function onDocumentCreated(DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $oldDocument
	 * @param DocumentInterface $document
	 * @param $value
	 */
	public function onDocumentUpdated(DocumentInterface $document, $value);

	/**
	 * @param DocumentInterface $document
	 */
	public function onDocumentDeleted(DocumentInterface $document);


	/**************************************************************************
	 * Database
	 **************************************************************************/
	/**
	 * @param Builder $query
	 */
	public function querySelectColumn(Builder $query, DocumentInterface $document);

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
	public function queryWhereCondition(Builder $query, $condition, $value, array $params);

	/**
	 * @return bool
	 */
	public function isChangeableDatabaseField();

	/**
	 * @param Blueprint $table
	 */
	public function setDatabaseFieldType(Blueprint $table);

	/**************************************************************************
	 * Render
	 **************************************************************************/
	/**
	 * @param DocumentInterface $document
	 * @param string|null $template
	 *
	 * @return string
	 */
	public function renderBackendTemplate(DocumentInterface $document, $template = null);
}