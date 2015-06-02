<?php namespace KodiCMS\CMS\Http\Controllers\System;

use UI;
use View;
use Assets;
use ModuleLoader;
use KodiCMS\CMS\Breadcrumbs\Collection as Breadcrumbs;
use KodiCMS\CMS\Navigation\Collection as Navigation;

class BackendController extends TemplateController
{
	/**
	 * @var bool
	 */
	public $authRequired = TRUE;

	/**
	 * @var Navigation
	 */
	public $navigation;

	/**
	 * @var Breadcrumbs
	 */
	public $breadcrumbs;

	public function boot()
	{
		$this->navigation = Navigation::init($this->request->getUri(), config('sitemap', []));
		$this->breadcrumbs = Breadcrumbs::factory();

		if (is_null(array_get($this->permissions, $this->getCurrentAction()))) {
			$this->permissions[$this->getCurrentAction()] = $this->getRouter()->currentRouteName();
		}
	}

	public function before()
	{
		$currentPage = Navigation::getCurrentPage();

		$this->breadcrumbs
			->add(UI::icon('home'), route('backend.dashboard'));

		if (!is_null($currentPage)) {
			$this->setTitle($currentPage->getName(), $currentPage->getUrl());
		}

		View::share('currentPage', $currentPage);

		parent::before();
	}

	public function after()
	{
		$this->template
			->with('breadcrumbs', $this->breadcrumbs)
			->with('navigation', $this->navigation)
			->with('bodyId', $this->getRouterPath())
			->with('theme', $this->currentUser->getCurrentTheme());

		parent::after();
	}

	/**
	 * @param $title
	 * @param string|null $url
	 * @return $this
	 */
	protected function setTitle($title, $url = NULL)
	{
		$this->breadcrumbs
			->add($title, $url);

		return parent::setTitle($title);
	}

	public function registerMedia()
	{
		parent::registerMedia();

		$this->templateScripts['ACE_THEME'] = config('cms.default_ace_theme', 'textmate');
		$this->templateScripts['DEFAULT_HTML_EDITOR'] = config('cms.default_html_editor', '');
		$this->templateScripts['DEFAULT_CODE_EDITOR'] = config('cms.default_code_editor', '');

		Assets::package(['libraries', 'core']);
		$this->includeModuleMediaFile($this->getRouterController());
		$this->includeMergedMediaFile('backendEvents', 'js/backendEvents');
	}
}
