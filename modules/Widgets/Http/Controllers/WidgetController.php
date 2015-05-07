<?php namespace KodiCMS\Widgets\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;
use Assets;
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

	public function getCreate()
	{
		$this->setTitle(trans('widgets::core.title.create'));

		$this->setContent('widgets.create');
	}

	public function postCreate()
	{

	}

	public function getLocation($id)
	{
		$this->setTitle(trans('widgets::core.title.location'));

		$this->setContent('widgets.location');
	}

	public function getEdit($id)
	{
		$this->setTitle(trans('widgets::core.title.edit'));

		$this->setContent('widgets.edit');
	}

	public function postEdit($id)
	{

	}

	public function getDelete($id)
	{

	}
}