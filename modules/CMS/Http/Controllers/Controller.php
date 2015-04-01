<?php namespace KodiCMS\CMS\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;


	/**
	 * Execute after an action executed
	 */
	public function after()
	{

	}

	/**
	 * Execute before an action executed
	 */
	public function before()
	{

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
		$this->before();

		$response = call_user_func_array([$this, $method], $parameters);

		$this->after($response);

		return $response;
	}
}
