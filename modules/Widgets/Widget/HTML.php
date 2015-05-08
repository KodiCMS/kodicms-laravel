<?php namespace KodiCMS\Widgets\Widget;


use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\Traits\WidgetRender;

class HTML extends DatabaseDecorator implements WidgetRenderable
{

	use WidgetRender;

	/**
	 * @return array
	 */
	public function prepareData()
	{
		return [

		];
	}

}