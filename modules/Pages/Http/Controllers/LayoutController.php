<?php namespace KodiCMS\Pages\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;

class LayoutController extends BackendController
{
	/**
	 * @var string
	 */
	public $templatePrefix = 'pages::';

	public function getIndex()
	{
		$layouts = [];

		$this->setContent('layouts.list', compact('layouts'));
	}
}