<?php namespace KodiCMS\CMS\Traits;

trait Singleton
{
	protected static $instance;

	final public static function getInstance()
	{
		if (!isset(self::$instance)) {
			$class = new ReflectionClass(__CLASS__);
			self::$instance = $class->newInstanceArgs(func_get_args());
		}

		return self::$instance;
	}

	final private function __clone(){}
	final private function __wakeup(){}
}