<?php namespace KodiCMS\Datasource\Repository;

use DatasourceManager;
use KodiCMS\Datasource\Exceptions\SectionException;
use KodiCMS\Datasource\Model\Section;
use KodiCMS\CMS\Repository\BaseRepository;

class SectionRepository extends BaseRepository
{
	/**
	 * @param Section $model
	 */
	public function __construct(Section $model)
	{
		parent::__construct($model);
	}

	/**
	 * @param array $data
	 * @return bool
	 * @throws \KodiCMS\CMS\Exceptions\ValidationException
	 */
	public function validateOnCreate(array $data = [])
	{
		$validator = $this->validator($data, [
			'name' => 'required'
		]);

		return $this->_validate($validator);
	}

	/**
	 * @param array $data
	 * @return bool
	 * @throws \KodiCMS\CMS\Exceptions\ValidationException
	 */
	public function validateOnUpdate(array $data = [])
	{
		$validator = $this->validator($data, [
			'name' => 'required',
		]);

		return $this->_validate($validator);
	}

	/**
	 * @param integer $sectionId
	 * @param integer $folderId
	 * @return bool
	 */
	public function moveToFolder($sectionId, $folderId)
	{
		$this->findOrFail($sectionId)->update([
			'folder_id' => (int) $folderId
		]);

		return true;
	}

	/**
	 * @param array $data
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 * @throws SectionException
	 */
	public function create(array $data = [])
	{
		if(is_null($type = array_get($data, 'type')))
		{
			throw new SectionException("Type not set");
		}

		if (is_null($typeObject = DatasourceManager::getTypeObject($type)))
		{
			throw new SectionException("Datasource type {$type} not found");
		}

		$section = parent::create($data);
		DatasourceManager::createTableSection($section);

		return $section;
	}
}