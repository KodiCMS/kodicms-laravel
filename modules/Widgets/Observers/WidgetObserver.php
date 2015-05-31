<?php namespace KodiCMS\Widgets\Observers;

use Request;

/**
 * Class WidgetObserver
 * @package KodiCMS\Widgets\Observers
 */
class WidgetObserver {

	/**
	 * @param \KodiCMS\Widgets\Model\Widget $ $widget
	 * @return void
	 */
	public function saving($widget)
	{
		$ids = Request::get('relatedWidgets', []);
		if (($key = array_search($widget->id, $ids)) !== false)
		{
			unset($ids[$key]);
		}

		$widget->related()->sync($ids);
	}

	/**
	 * @param \KodiCMS\Widgets\Model\Widget $widget
	 * @return void
	 */
	public function created($widget)
	{

	}

	/**
	 * @param \KodiCMS\Widgets\Model\Widget $ $widget
	 * @return void
	 */
	public function updating($widget)
	{

	}

	/**
	 * @param \KodiCMS\Widgets\Model\Widget $ $widget
	 * @return void
	 */
	public function deleting($widget)
	{

	}

	/**
	 * @param \KodiCMS\Widgets\Model\Widget $ $widget
	 * @return bool
	 */
	public function deleted($widget)
	{

	}

}