<?php namespace KodiCMS\Pages;

class BehaviorManager {

	protected static $behaviors = [];

	public static function init()
	{
		foreach (config('behavior') as $name => $params)
		{
			static::$behaviors[$name] = $params;
		}
	}

	public static function load()
	{

	}

	/**
	 * @return array
	 */
	public function getBehaviorsList()
	{
		return static::$behaviors;
	}
}