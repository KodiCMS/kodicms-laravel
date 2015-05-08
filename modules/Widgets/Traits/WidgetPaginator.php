<?php namespace KodiCMS\Widgets\Traits;

trait WidgetPaginator {

	/**
	 * @param int $offset
	 */
	public function setSettingListOffset($offset)
	{
		$this->settings['list_offset'] = (int) $offset;
	}

	/**
	 * @param int $size
	 */
	public function setSettingListSize($size)
	{
		$this->settings['list_size'] = (int) $size;
	}
}