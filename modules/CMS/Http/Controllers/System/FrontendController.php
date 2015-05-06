<?php namespace KodiCMS\CMS\Http\Controllers\System;

use Assets;

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

		$file = $this->getRouterController();
		if (app('module.loader')->findFile('resources/js', $file, 'js')) {
			Assets::js('controller.' . $file, backend_resources_url() . '/js/' . $file . '.js', 'core', false);
		}

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