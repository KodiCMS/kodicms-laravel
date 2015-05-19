<?php namespace KodiCMS\Datasource;

class DatasourceManager {

	/**
	 * @var array
	 */
	protected $config = [];

	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;

		foreach ($this->config as $type => $data)
		{
			if (!SectionType::isValid($data)) continue;
			$this->types[$type] = new SectionType($type, $data);
		}
	}

	/**
	 * @param string $type
	 * @return bool
	 */
	public function typeExists($type)
	{
		return isset($this->types[$type]);
	}

	public function getSectionsTree()
	{

	}

	public function getSections(array $types = null)
	{
		$query = DB::table('datasource');

		if(!empty($types))
		{
			$query->whereIn('type', $types);
		}

		$sections = [];

		foreach($query->get() as $row)
		{
			if(!$this->typeExists($row->type)) continue;
			$sections[$row->id] = $section = new Section((array) $row);
		}

		return $sections;
	}

	/**
	 * @param integer $sectionId
	 * @param integer $folderId
	 */
	public function moveSectioToFolde($sectionId, $folderId)
	{

	}

	/**
	 * @return array
	 */
	public function getAvailableSectionTypes()
	{
		return $this->types;
	}
}