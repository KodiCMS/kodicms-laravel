<?php namespace KodiCMS\Pages\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\CMS\Assets\Core as Assets;
use KodiCMS\Pages\Model\Page;

class PageController extends BackendController
{
	/**
	 * @var string
	 */
	public $templatePreffix = 'pages::';

	/**
	 * @var array
	 */
	public $allowedActions = ['children'];

	public function getIndex()
	{
		Assets::package(['nestable', 'editable']);

		$this->templateScripts['PAGE_STATUSES'] = array_map(function ($value, $key) {
			return ['id' => $key, 'text' => $value];
		}, Page::statuses(), array_keys(Page::statuses()));

		$this->setContent('index', [
			'page' => Page::find(1),
			'childrenPages' => [],
		]);
	}

	public function getEdit($id)
	{

	}

	public function getAdd()
	{

	}

	public function postDelete()
	{

	}
}