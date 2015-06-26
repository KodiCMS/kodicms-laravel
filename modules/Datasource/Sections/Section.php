<?php namespace KodiCMS\Datasource\Sections;

use DB;
use ACL;
use DatasourceManager;
use KodiCMS\Datasource\Document;
use KodiCMS\Datasource\Model\SectionFolder;
use KodiCMS\Datasource\SectionType;
use KodiCMS\Support\Traits\Settings;
use KodiCMS\Datasource\Fields\String;
use KodiCMS\Datasource\Fields\Primary;
use KodiCMS\Datasource\SectionHeadline;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Model\Section as SectionModel;

class Section implements SectionInterface
{
	use Settings {
		setSetting as protected setSettingTrait;
	}

	protected $tableName = 'test';

	/**
	 * @var SectionModel
	 */
	protected $model;

	/**
	 * @var SectionType
	 */
	protected $type;

	/**
	 * @var SectionHeadline
	 */
	protected $headline;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var array
	 */
	protected $fields = [];

	/**
	 * @param SectionModel $model
	 */
	public function __construct(SectionModel $model)
	{
		$this->model = $model;
		$this->type = DatasourceManager::getTypeObject($model->type);
		$this->settings = $model->settings;

		$this->initialize();
	}


	protected function initialize()
	{
		$this->initializeFields();

		$headlineClass = $this->getHeadlineClass();
		$this->headline = new $headlineClass($this);
	}

	protected function initializeFields()
	{
		$fields = $this->model->fields();
		foreach($fields->get() as $field)
		{
			$this->fields[$field->id] = $field->toField();
		}
	}

	/**
	 * @return SectionHeadline
	 */
	public function getHeadline()
	{
		return $this->headline;
	}

	/**
	 * @return string
	 */
	public function getHeadlineClass()
	{
		return SectionHeadline::class;
	}

	// TODO реализовать тулбар
	public function getToolbar()
	{
		return null;
	}

	/**
	 * @return array
	 */
	public function systemFields()
	{
		return [
			new Primary(null, [
				'key' => 'id',
				'name' => 'ID'
			]),
			new String(null, [
				'key' => 'header',
				'name' => 'Header'
			])
		];
	}

	/**
	 * @return SectionFolder|null
	 */
	public function getFolder()
	{
		return $this->model->folder;
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->model->id;
	}

	/**
	 * @return SectionType|null
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return SectionModel
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setSetting($name, $value = null)
	{
		$return = $this->setSettingTrait($name, $value);
		$this->model->settings = $this->settings;
		return $return;
	}

	/**
	 * @return string
	 */
	public function getTableName()
	{
		return 'ds_' . $this->tableName . $this->getId();
	}


	/**************************************************************************
	 * Documents
	 **************************************************************************/

	/**
	 * @return Document
	 */
	public function getEmptyDocument()
	{
		$documentClass = $this->getDocumentClass();
		return new $documentClass($this);
	}

	/**
	 * @param integer $id
	 * @return Document
	 */
	public function getDocumentById($id)
	{
		return $this->getEmptyDocument()->loadById($id);
	}

	/**
	 * @return string
	 */
	public function getDocumentClass()
	{
		return Document::class;
	}

	/**************************************************************************
	 * ACL
	 **************************************************************************/

	/**
	 * @return array
	 */
	public function getAclActions()
	{
		return [
			[
				'action' => 'section.view',
				'description' => 'View section'
			], [
				'action' => 'section.edit',
				'description' => 'Edit section'
			], [
				'action' => 'section.remove',
				'description' => 'Remove section'
			], [
				'action' => 'document.view',
				'description' => 'View documents'
			], [
				'action' => 'document.create',
				'description' => 'Create documents'
			], [
				'action' => 'document.edit',
				'description' => 'Edit documents'
			], [
				'action' => 'document.remove',
				'description' => 'Remove documents'
			]
		];
	}

	/**
	 * Пользователь - создатель раздела
	 *
	 * @param integer|null $userId
	 * @return boolean
	 */
	public function userIsCreator($userId = null)
	{
		if ($userId === null)
		{
			$userId = auth()->user()->id;
		}

		return ACL::isAdmin($userId) or ($this->model->created_by_id == (int) $userId);
	}

	/**
	 * Проверка прав доступа
	 *
	 * @param string $acl
	 * @param bool $checkOwn
	 * @param null|integer $userId
	 * @return bool
	 */
	public function userHasAccess($acl = 'section.edit', $checkOwn = true, $userId = null)
	{
		return (ACL::check('ds_id.' . $this->getId() . '.' . $acl) OR ($checkOwn AND $this->userIsCreator($userId)));
	}

	/**
	 * Проверка прав на редактирование
	 *
	 * @param null|integer $userId
	 * @return bool
	 */
	public function userHasAccessEdit($userId = null)
	{
		return $this->userHasAccess('section.edit', true, $userId);
	}

	/**
	 * Проверка прав на редактирование
	 *
	 * @param null|integer $userId
	 * @return bool
	 */
	public function userHasAccessCreate($userId = null)
	{
		return ACL::check($this->getType() . '.' . 'section.create');
	}

	/**
	 * Проверка прав на просмотр
	 *
	 * @param null|integer $userId
	 * @return boolean
	 */
	public function userHasAccessView($userId = null)
	{
		return $this->userHasAccess('section.view', true, $userId);
	}

	/**
	 * Проверка прав на удаление
	 *
	 * @param null|integer $userId
	 * @return boolean
	 */
	public function userHasAccessRemove($userId = null)
	{
		return $this->userHasAccess('section.remove', true, $userId);
	}
}