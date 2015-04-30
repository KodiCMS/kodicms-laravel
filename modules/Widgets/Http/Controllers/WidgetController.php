<?php namespace KodiCMS\Widgets\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\CMS\Assets\Core as Assets;
use KodiCMS\Widgets\Model\Widget;

class WidgetController extends BackendController {

	/**
	 * @var string
	 */
	public $moduleNamespace = 'widgets::';

	public function getIndex()
	{
		Assets::package(['editable']);

		$widgets = Widget::paginate();

		$this->setContent('widgets.list', compact('widgets'));
	}

}