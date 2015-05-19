<?php namespace KodiCMS\Datasource;

use DB;
use KodiCMS\Support\Facades\FieldManager;
use Schema;
use KodiCMS\CMS\Traits\Settings;
use KodiCMS\Datasource\Contracts\SectionInterface;

class Section implements SectionInterface
{
	use Settings;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var array
	 */
	protected $systemFields = [];

	/**
	 * @var array
	 */
	protected $customFields = [];

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

	/**
	 * @param array $values
	 */
	public function __construct(array $values)
	{
		$settings = array_pull($values, 'settings', []);

		foreach($values as $key => $value)
		{
			$this->{snake_case($key)} = $value;
		}

		$this->setSettings($settings);

		if ($this->isLoaded())
		{
			$this->loadFieldsFromDatabase();
		}
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->systemFields + $this->customFields;;
	}

	/**
	 * @return array
	 */
	public function getSystemFieldsKeys()
	{
		return array_keys($this->getSystemFields());
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
		return $this->tableName . $this->getId();
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

	public function create(array $values)
	{

	}

	public function update(array $values)
	{

	}

	public function delete()
	{

	}

	public function migrate()
	{
		$fields = $this->getFields();
		$section = $this;

		Schema::create($this->getTableName(), function (Blueprint $table) use($fields, $section) {
			foreach($fields as $field)
			{
				$field->getDatabaseFieldType($table);
				$field->migrate($section);
			}
		});
	}

	/**
	 * @return array
	 */
	protected function loadFieldsFromDatabase()
	{
		$query = DB::table('datasource_fields')
			->where('ds_id', $this->getId());

		$systemFieldsKeys = $this->getSystemFieldsKeys();

		foreach($query->get() as $item)
		{
			$field = FieldManager::make((array) $item);

			if(in_array($item->key, $systemFieldsKeys))
			{
				$this->systemFields[$item->key] = $field;
			}
			else
			{
				$this->customFields[$item->key] = $field;
			}
		}

		return $this->customFields;
	}

	/**
	 * @return array
	 */
	protected function getSystemFields()
	{
		return [
			'id' => new Fields\Primary('id')
		];
	}
}