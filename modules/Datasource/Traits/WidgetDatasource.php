<?php namespace KodiCMS\Datasource\Traits;

trait WidgetDatasource
{
	/**
	 * @return bool
	 */
	public function isDatasourceSelected()
	{
		return $this->getSectionId() > 0;
	}

	/**
	 * @return integer
	 */
	public function getSectionId()
	{
		return (int) $this->section_id;
	}
}