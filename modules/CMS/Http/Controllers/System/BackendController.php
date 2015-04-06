<?php namespace KodiCMS\CMS\Http\Controllers\System;

use KodiCMS\CMS\Breadcrumbs\Collection as Breadcrumbs;
use KodiCMS\CMS\Navigation\Collection as Navigation;
use KodiCMS\CMS\Assets\Core as Assets;

class BackendController extends TemplateController
{
	/**
	 * @var bool
	 */
	public $authRequired = TRUE;

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

	public function boot()
	{
		$this->navigation = Navigation::init($this->request->getUri(), config('sitemap', []));
		$this->breadcrumbs = Breadcrumbs::factory();

		if(is_null(array_get($this->permissions, $this->getCurrentAction()))) {
			$this->permissions[$this->getCurrentAction()] = $this->getRouter()->currentRouteName();
		}
	}

	public function before()
	{
		$currentPage = Navigation::getCurrentPage();

		$this->breadcrumbs
			->add(\UI::icon('home'), route('backend.dashboard'));

		if(!is_null($currentPage)){
			$this->breadcrumbs->add($currentPage->getName(), $currentPage->getUrl());
			$this->setTitle($currentPage->getName());
		}

		parent::before();
	}

	public function after()
	{
		$this->template
			->with('breadcrumbs', $this->breadcrumbs)
			->with('navigation', $this->navigation)
			->with('bodyId', $this->getRouterPath())
			->with('theme', config('cms.theme.default'))
			->with('requestType', $this->request->ajax() ? 'request.iframe' : 'request.get');

		\View::share('currentUser', $this->currentUser);

		parent::after();
	}

	public function registerMedia()
	{
		parent::registerMedia();

		$this->templateScripts['ACE_THEME'] = config('cms.wysiwyg.ace.theme', 'textmate');
		$this->templateScripts['DEFAULT_HTML_EDITOR'] = config('cms.wysiwyg.default_html_editor', '');
		$this->templateScripts['DEFAULT_CODE_EDITOR'] = config('cms.wysiwyg.default_code_editor', '');

		Assets::package(['libraries', 'core']);


		$file = $this->getRouterController();
		if (app('module.loader')->findFile('resources/js', $file, 'js'))
		{
			Assets::js('controller.' . $file, \CMS::backendResourcesURL() . '/js/' . $file . '.js', 'global', FALSE, 999);
		}

		// TODO: разобраться с подключением событий и локалей в контроллер
		//Assets::group('global', 'events', '<script type="text/javascript">' . Assets::merge_files('js/events', 'js') . '</script>', 'global');
	}
}
