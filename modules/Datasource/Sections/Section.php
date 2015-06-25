<?php namespace KodiCMS\Datasource\Sections;

use DB;
use DatasourceManager;
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
		return new SectionHeadline;
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
}