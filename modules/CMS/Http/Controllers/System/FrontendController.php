<?php namespace KodiCMS\CMS\Http\Controllers\System;

use KodiCMS\CMS\Assets\Core as Assets;

class FrontendController extends TemplateController
{
	/**
	 * @var  \View  page template
	 */
	public $template = 'cms::app.frontend';

	public function registerMedia()
	{
		parent::registerMedia();
		Assets::package(['libraries', 'core']);

		$this->includeMedia('frontendEvents', 'js/frontendEvents', 'js');
	}

	public function after()
	{
		$this->template
			->with('bodyId', $this->getRouterPath())
			->with('theme', config('cms.theme.default'));

		parent::after();
	}
}