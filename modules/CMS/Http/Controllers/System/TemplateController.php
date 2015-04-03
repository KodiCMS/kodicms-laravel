<?php namespace KodiCMS\CMS\Http\Controllers\System;

use Illuminate\Support\Facades\View;
use KodiCMS\CMS\Assets\Core as Assets;

class TemplateController extends Controller
{

	/**
	 * @var  \View  page template
	 */
	public $template = 'cms::app.backend';

	/**
	 *
	 * @var \Breadcrumbs
	 */
	public $breadcrumbs;

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
			$content = view($view, $data);
			$this->template->with('content', $content);

			return $content;
		}

		return view($view, $data);
	}

	public function before()
	{
		parent::before();

		if ($this->autoRender === TRUE)
		{
			$this->registerMedia();
		}

		View::share('adminDir', \CMS::backendPath());
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
			'BASE_URL' => url(\CMS::backendPath()),
			'BACKEND_PATH' => \CMS::backendPath(),
			'BACKEND_RESOURCES' => \CMS::backendResourcesURL(),
			'PUBLIC_URL' => url(),
			'LOCALE' => \Lang::getLocale(),
			'ROUTE' => $this->getRouter()->currentRouteAction(),
			'ROUTE_PATH' => $this->getRouterPath(),
			'USER_ID' => \Auth::id()
		];
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

		if (is_null($response) && !is_null($this->template)) {
			$response = $this->template;
		}

		return $response;
	}
}
