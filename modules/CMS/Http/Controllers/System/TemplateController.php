<?php namespace KodiCMS\CMS\Http\Controllers\System;

use App;
use Assets;
use Lang;
use ModulesFileSystem;
use View;

class TemplateController extends Controller
{
	/**
	 * @var  \View  page template
	 */
	public $template = 'cms::app.backend';

	/**
	 * @var  boolean  auto render template
	 **/
	public $autoRender = true;

	/**
	 * @var boolean
	 */
	public $onlyContent = false;

	/**
	 * @var array
	 */
	public $templateScripts = [];

	/**
	 * @param $view
	 * @param array $data
	 * @return View
	 */
	public function setContent($view, array $data = [])
	{
		if (!is_null($this->template))
		{
			$content = view($this->wrapNamespace($view), $data);
			$this->template->with('content', $content);

			return $content;
		}

		return view($this->wrapNamespace($view), $data);
	}

	public function before()
	{
		parent::before();

		if ($this->autoRender === TRUE)
		{
			$this->registerMedia();
		}

		View::share('adminDir', backend_url());
		View::share('controllerAction', $this->getCurrentAction());
		View::share('currentUser', $this->currentUser);
		View::share('requestType', $this->requestType);

		// Todo: подумать нужно ли передавать во view название модуля
		//View::share('currentModule', substr($this->getModuleNamespace(), 0, -2));
	}

	public function after()
	{
		parent::after();

		if ($this->autoRender === TRUE)
		{
			if ($this->onlyContent)
			{
				$this->template = $this->template->content;
			}
			else
			{
				Assets::group('global', 'templateScripts', '<script type="text/javascript">' . $this->getTemplateScriptsAsString() . '</script>', 'global');
			}
		}
	}

	public function registerMedia()
	{
		$this->templateScripts = [
			'CURRENT_URL' => $this->request->url(),
			'SITE_URL' => url(),
			'BASE_URL' => url(backend_url()),
			'BACKEND_PATH' => backend_url(),
			'BACKEND_RESOURCES' => App::backendResourcesURL(),
			'PUBLIC_URL' => url(),
			'LOCALE' => Lang::getLocale(),
			'ROUTE' => !is_null($this->getRouter()) ? $this->getRouter()->currentRouteAction() : null,
			'ROUTE_PATH' => $this->getRouterPath(),
			'REQUEST_TYPE' => $this->requestType,
			'USER_ID' => \Auth::id(),
			'MESSAGE_ERRORS' => view()->shared('errors')->getBag('default'),
			'MESSAGE_SUCCESS' => (array) $this->session->get('success', []),
		];
	}

	/**
	 * @return string
	 */
	public function getTemplateScriptsAsString()
	{
		$scrpit = '';
		foreach ($this->templateScripts as $var => $value)
		{
			$value = json_encode($value);
			$scrpit .= "var {$var} = {$value};\n";
		}

		return $scrpit;
	}

	/**
	 * @param string $key
	 * @param string $file
	 */
	public function includeMergedMediaFile($key, $file)
	{
		$mediaContent = '<script type="text/javascript">' . Assets::mergeFiles($file, 'js') . "</script>";
		Assets::group('global', $key, $mediaContent, 'global');
	}

	/**
	 * @param $filename
	 */
	public function includeModuleMediaFile($filename)
	{
		if (ModulesFileSystem::findFile('resources/js', $filename, 'js'))
		{
			Assets::js('include.' . $filename, backend_resources_url() . '/js/' . $filename . '.js', 'core', false);
		}
	}

	/**
	 * Execute an action on the controller.
	 *
	 * @param  string $method
	 * @param  array $parameters
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function callAction($method, $parameters)
	{
		if ($this->autoRender === TRUE)
		{
			$this->setupLayout();
		}

		$response = parent::callAction($method, $parameters);

		if (is_null($response) && $this->autoRender === TRUE && !is_null($this->template)) {
			$response = $this->template;
		}

		return $response;
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return $this
	 */
	protected function setupLayout()
	{
		if (!is_null($this->template))
		{
			$this->template = view($this->template);
		}

		return $this;
	}

	/**
	 * Set the layout used by the controller.
	 *
	 * @param $name
	 * @return $this
	 */
	protected function setLayout($name)
	{
		$this->template = $name;
		return $this;
	}

	/**
	 * @param $title
	 * @return $this
	 */
	protected function setTitle($title)
	{
		// Initialize empty values
		$this->template->with('title', $title);
		return $this;
	}
}
