<?php namespace KodiCMS\CMS\Http\Controllers\System;

use Assets;
use ModuleLoader;

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

		$this->includeModuleMediaFile($this->getRouterController());
		$this->includeMergedMediaFile('frontendEvents', 'js/frontendEvents', 'js');
	}

	public function after()
	{
		$this->template
			->with('bodyId', $this->getRouterPath())
			->with('theme', config('cms.theme.default'));

		parent::after();
	}
}