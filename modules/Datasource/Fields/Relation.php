<?php namespace KodiCMS\Datasource\Fields;

use DatasourceManager;
use KodiCMS\Datasource\Model\Field;

class Relation extends Field
{
	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	 */
	protected $with = ['relatedSection'];

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
		return $this->getDBKey() . '_related';
	}

	/**
	 * @return string
	 */
	public function getRelationName()
	{
		return camel_case($this->getDBKey() . '_relation');
	}
}