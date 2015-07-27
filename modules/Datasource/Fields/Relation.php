<?php namespace KodiCMS\Datasource\Fields;

use DatasourceManager;
use KodiCMS\Datasource\Model\Field;

class Relation extends Field
{
	/**
	 * @return array
	 */
	public function getSectionList()
	{
		return DatasourceManager::getSectionsFormHTML();
	}

	/**
	 * @return integer
	 */
	public function getRelatedSectionId()
	{
		return $this->related_ds;
	}

	/**
	 * @return string
	 */
	public function getRelatedDBKey()
	{
		return $this->getDBKey() . '_relation';
	}
}