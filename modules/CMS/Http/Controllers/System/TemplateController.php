<?php namespace KodiCMS\CMS\Http\Controllers\System;

use CMS;
use View;
use Lang;
use Assets;

class TemplateController extends Controller
{
	/**
	 * @var  \View  page template
	 */
	public $template = 'cms::app.backend';

	/**
	 * @var string
	 */
	public $moduleNamespace = 'cms::';

	/**
	 * @var  boolean  auto render template
	 **/
	public $autoRender = TRUE;

	/**
	 *
	 * @var boolean
	 */
	public $onlyContent = FALSE;

	/**
	 * @var array
	 */
	public $templateScripts = [];

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return $this
	 */
	protected function setupLayout()
	{
		if (!is_null($this->template)) {
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
		$this->template
			->with('title', $title);

		return $this;
	}

	/**
	 * @param $view
	 * @param array $data
	 * @return View
	 */
	public function setContent($view, array $data = [])
	{
		if (!is_null($this->template)) {
			$content = view($this->moduleNamespace . $view, $data);
			$this->template->with('content', $content);

			return $content;
		}

		return view($this->moduleNamespace . $view, $data);
	}

	public function before()
	{
		parent::before();

		if ($this->autoRender === TRUE)
		{
			$this->registerMedia();
		}

		View::share('adminDir', \CMS::backendPath());
		View::share('controllerAction', $this->getCurrentAction());
		View::share('currentUser', $this->currentUser);
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
				$scrpit = '';
				foreach ($this->templateScripts as $var => $value)
				{
					$value = json_encode($value);

					$scrpit .= "var {$var} = {$value};\n";
				}

				Assets::group('global', 'templateScripts', '<script type="text/javascript">' . $scrpit . '</script>', 'global');
			}
		}
	}

	public function registerMedia()
	{
		$this->templateScripts = [
			'CURRENT_URL' => $this->request->url(),
			'SITE_URL' => url(),
			'BASE_URL' => url(CMS::backendPath()),
			'BACKEND_PATH' => CMS::backendPath(),
			'BACKEND_RESOURCES' => CMS::backendResourcesURL(),
			'PUBLIC_URL' => url(),
			'LOCALE' => Lang::getLocale(),
			'ROUTE' => !is_null($this->getRouter()) ? $this->getRouter()->currentRouteAction() : null,
			'ROUTE_PATH' => $this->getRouterPath(),
			'USER_ID' => \Auth::id(),
			'MESSAGE_ERRORS' => view()->shared('errors')->getBag('default'),
			'MESSAGE_SUCCESS' => (array) $this->session->get('success', []),
		];
	}

	/**
	 * @param string $key
	 * @param string $file
	 * @param string $ext
	 */
	public function includeMedia($key, $file, $ext)
	{
		$mediaContent = '<script type="text/javascript">' . Assets::mergeFiles($file, $ext) . "</script>";
		Assets::group('global', $key, $mediaContent, 'global');
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
}
