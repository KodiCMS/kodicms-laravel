<?php namespace KodiCMS\CMS\Http\Controllers\System;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class TemplateController extends Controller {

	protected $template = 'app';

	public function __construct()
	{
		view()->composer(['app', 'appClear'], function($view) {
			$view->with('bodyId', Route::currentRouteName());
		});
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return $this
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->template))
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
	 * @param null $subTitle
	 * @return $this
	 */
	protected function setTitle($title, $subTitle = NULL)
	{
		View::share('pageTitle', $title);
		View::share('pageSubtitle', $subTitle);

		return $this;
	}

	/**
	 * Show the user profile.
	 */
	public function setContent($view, $data = [])
	{
		if ( ! is_null($this->template))
		{
			$content = view($view, $data);
			$this->template->with('content', $content);

			return $content;
		}

		return view($view, $data);

	}

	/**
	 * Execute an action on the controller.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function callAction($method, $parameters)
	{
		$this->setupLayout();

		$response = parent::callAction($method, $parameters);

		if (is_null($response) && ! is_null($this->template))
		{
			$response = $this->template;
		}

		return $response;
	}
}
