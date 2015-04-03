<?php namespace KodiCMS\CMS\Http\Controllers\System;

use KodiCMS\CMS\Breadcrumbs\Collection as Breadcrumbs;
use KodiCMS\CMS\Navigation\Collection as Navigation;

class BackendController extends TemplateController
{
	/**
	 * @var bool
	 */
	public $authRequired = FALSE;

	/**
	 *
	 * @var Navigation
	 */
	public $navigation;

	/**
	 *
	 * @var Breadcrumbs
	 */
	public $breadcrumbs;

	public function before()
	{
		$this->navigation = Navigation::init($this->request->getUri(), config('sitemap', []));
		$this->breadcrumbs = Breadcrumbs::factory();

		$this->breadcrumbs
			->add(\UI::icon('home'), route('backendDashboard'));

		parent::before();
	}

	public function after()
	{
		$this->template
			->with('breadcrumbs', $this->breadcrumbs)
			->with('navigation', $this->navigation)
			->with('bodyId', $this->getRouterPath())
			->with('requestType', $this->request->ajax() ? 'request.iframe' : 'request.get');

		parent::after();
	}
}
