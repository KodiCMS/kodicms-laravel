<?php namespace KodiCMS\Dashboard\Widget;

class MiniCalendar extends Decorator
{
	/**
	 * @var array
	 */
	protected $size = [
		'x' => 3,
		'y' => 1,
		'max_size' => [5, 1],
		'min_size' => [3, 1]
	];

	/**
	 * @var string
	 */
	protected $frontendTemplate = 'dashboard::widgets.mini_calendar.template';

	/**
	 * @return array
	 */
	public function prepareData()
	{
		return [];
	}
}