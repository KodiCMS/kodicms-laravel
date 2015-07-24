<?php namespace KodiCMS\Datasource\Model;

use DatasourceManager;
use KodiCMS\Datasource\Document;
use KodiCMS\Datasource\SectionHeadline;
use KodiCMS\Datasource\Fields\Primitive\String;
use KodiCMS\Datasource\Fields\Primitive\Primary;
use KodiCMS\Datasource\Fields\Primitive\Boolean;
use KodiCMS\Datasource\Fields\Primitive\Timestamp;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Exceptions\SectionException;

class Section extends DatasourceModel implements SectionInterface
{
	/**
	 * @var SectionHeadlineInterface
	 */
	protected $headline;

	/**
	 * @var string
	 */
	protected $table = 'datasources';

	/**
	 * @var string
	 */
	protected $sectionTablePrefix = 'ds_';

	/**
	 * @var string
	 */
	protected $documentPrimaryKey = 'id';

	/**
	 * @var string
	 */
	protected $documentTitleKey = 'header';

	/**
	 * @var string
	 */
	protected $sectionTableName = 'test';

	/**
	 * @var array
	 */
	protected $sectionFields = [];

	/**
	 * @var array
	 */
	protected $sectionSettings = [];

	/**
	 * @var
	 */
	protected $sectionType;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'folder_id', 'type', 'name', 'description',
		'is_indexable', 'created_by_id', 'settings'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'folder_id' => 'integer',
		'type' => 'string',
		'name' => 'string',
		'description' => 'string',
		'is_indexable' => 'boolean',
		'created_by_id' => 'integer',
		'settings' => 'array'
	];

	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getDocumentPrimaryKey()
	{
		return $this->documentPrimaryKey;
	}

	/**
	 * @return string
	 */
	public function getDocumentTitleKey()
	{
		return $this->documentTitleKey;
	}

	/**
	 * @return integer
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return integer
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getSectionTableName()
	{
		return $this->sectionTablePrefix . $this->sectionTableName . $this->getId();
	}

	/**
	 * @return string
	 */
	public function getLink()
	{
		return route('backend.datasource.list', [$this->id]);
	}

	/**
	 * @return integer
	 */
	public function getIcon()
	{
		return $this->getType()->getIcon();
	}

	/**************************************************************************
	 * Fields
	 **************************************************************************/
	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->sectionFields;
	}

	/**
	 * @return array
	 */
	public function getSystemFields()
	{
		return [
			new Primary([
				'key' => 'id',
				'name' => 'ID'
			]),
			new String([
				'key' => 'header',
				'name' => 'Header'
			]),
			new Boolean([
				'key' => 'published',
				'name' => 'Published'
			]),
			new Timestamp([
				'key' => static::CREATED_AT,
				'name' => 'Created At'
			]),
			new Timestamp([
				'key' => static::UPDATED_AT,
				'name' => 'Updated At'
			])
		];
	}

	protected function initializeFields()
	{
		foreach ($this->fields()->get() as $field)
		{
			$this->sectionFields[$field->getId()] = $field;
		}
	}

	/**************************************************************************
	 * Headline
	 **************************************************************************/
	/**
	 * @return SectionHeadlineInterface
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

	/**************************************************************************
	 * Toolbar
	 **************************************************************************/
	// TODO реализовать тулбар
	public function getToolbar()
	{
		return null;
	}

	/**************************************************************************
	 * Type
	 **************************************************************************/
	/**
	 * @return \KodiCMS\Datasource\SectionType|null
	 * @throws SectionException
	 */
	public function getType()
	{
		if ($this->sectionType)
		{
			return $this->sectionType;
		}

		if (is_null($typeObject = DatasourceManager::getTypeObject($this->type)))
		{
			throw new SectionException("Datasource type {$this->type} not found");
		}

		return $this->sectionType = $typeObject;
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

		return new $documentClass([], $this);
	}

	/**
	 * @param integer $id
	 * @return Document
	 */
	public function getDocumentById($id)
	{
		return $this->getEmptyDocument()->findOrFail($id);
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

		return ACL::isAdmin($userId) or ($this->created_by_id == (int) $userId);
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
		return (acl_check('ds_id.' . $this->getId() . '.' . $acl) OR ($checkOwn AND $this->userIsCreator($userId)));
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
		return acl_check($this->type . '.' . 'section.create');
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

	/**************************************************************************
	 * Relations
	 **************************************************************************/

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function fields()
	{
		return $this->hasMany('KodiCMS\Datasource\Model\Field', 'ds_id')->orderBy('position');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function folder()
	{
		return $this->belongsTo('KodiCMS\Datasource\Model\SectionFolder', 'folder_id')->orderBy('position');
	}

	/**************************************************************************
	 * Other
	 **************************************************************************/
	public function initialize()
	{
		if ($this->initialized)
		{
			return;
		}

		$this->initializeFields();

		$headlineClass = $this->getHeadlineClass();
		$this->headline = new $headlineClass($this);

		$this->setSettings((array) $this->settings);

		$this->initialized = true;
	}

	/**
	 * @return DatasourceManagerInterface
	 */
	public static function getManagerClass()
	{
		return DatasourceManager::getFacadeRoot();
	}
}