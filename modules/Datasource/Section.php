<?php namespace KodiCMS\Datasource;

use Schema;
use KodiCMS\CMS\Traits\Settings;
use KodiCMS\Datasource\Contracts\SectionInterface;

abstract class Section implements SectionInterface
{
	use Settings;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var array
	 */
	protected $fields = [];

	/**
	 * @var string
	 */
	protected $tableName;

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $description = '';

	/**
	 * @var int
	 */
	protected $countDocs = 0;

	/**
	 * @var int
	 */
	protected $folderId = 0;

	/**
	 * @var integer
	 */
	protected $createdById = null;

	/**
	 * @var bool
	 */
	protected $isIndexadle = false;

	/**
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * @var bool
	 */
	protected $timestamps = false;

	public function __construct()
	{

	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return int
	 */
	public function getCountDocs()
	{
		return $this->countDocs;
	}

	/**
	 * @return int
	 */
	public function getFolderId()
	{
		return $this->folderId;
	}

	/**
	 * @return int
	 */
	public function getCreatedById()
	{
		return $this->createdById;
	}

	/**
	 * @return boolean
	 */
	public function isIndexadle()
	{
		return $this->isIndexadle;
	}

	/**
	 * @return string
	 */
	public function getDefaultIcon()
	{
		return 'folder-open-o';
	}

	/**
	 * @return string
	 */
	public function getKeyName()
	{
		return $this->primaryKey;
	}

	/**
	 * @return string
	 */
	public function getQualifiedKeyName()
	{
		return $this->getTableName().'.'.$this->getKeyName();
	}

	/**
	 * @return bool
	 */
	public function usesTimestamps()
	{
		return $this->timestamps;
	}

	/**
	 * @return bool
	 */
	public function isLoaded()
	{
		return $this->id > 0;
	}

	public function migrate()
	{
		$fields = $this->getFields();

		Schema::create($this->getTableName(), function (Blueprint $table) use($fields) {
			foreach($fields as $field)
			{
				$field->getDatabaseFieldType($table);
			}
		});
	}

	public function create(array $values)
	{

	}

	public function update(array $values)
	{

	}

	public function delete()
	{

	}

	/**
	 * @return array
	 */
	protected function getFieldTypes()
	{
		return [
			new Fields\Primary('id'),
			new Fields\String('header')
		];
	}
}