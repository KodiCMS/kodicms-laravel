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

	/**
	 * @return \Illuminate\Database\Eloquent\Model|SectionInterface|null
	 */
	public function getSection()
	{
		if (is_null($this->section) and $this->isDatasourceSelected())
		{
			$this->section = $this->sectionRepository->findOrFail($this->getSectionId());
		}

		return $this->section;
	}
}