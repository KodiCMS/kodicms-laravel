<?php namespace KodiCMS\CMS\Helpers;


use DB;
use Cache;

/**
 * Class DatabaseConfig
 * TODO: убрать статику. DB и Cache перевести на IoC. Greabock 20.05.2015
 *
 *
 * @package KodiCMS\CMS\Helpers
 */
class DatabaseConfig
{
	/**
	 * @var string
	 */
	protected static $cacheKey = 'databaseConfig';

	/**
	 * @var array
	 */
	protected static $loadedKeys = [];

	/**
	 * @return array
	 */
	final public static function get()
	{
		$databaseConfig = Cache::rememberForever(static::$cacheKey, function () {
			return DB::table('config')->get();
		});

		foreach($databaseConfig as $row)
		{
			static::$loadedKeys[$row->group][$row->key] = json_decode($row->value, true);
		}

		return static::$loadedKeys;
	}

	/**
	 * @param string $group
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	final public static function set($group, $key, $value)
	{
		$value = json_encode($value);

		if (isset(static::$loadedKeys[$group][$key]))
		{
			static::update($group, $key, $value);
		}
		else
		{
			static::insert($group, $key, $value);
		}

		Cache::forget(static::$cacheKey);

		return TRUE;
	}

	/**
	 * @param array $settings
	 */
	final public static function save(array $settings)
	{
		foreach ($settings as $group => $values)
		{
			if (is_array($values))
			{
				foreach ($values as $key => $value)
				{
					static::set($group, $key, $value);
				}
			}
			else
			{
				static::set('site', $group, $values);
			}
		}
	}

	/**
	 * Insert the config values into the table
	 *
	 * @param string      $group  The config group
	 * @param string      $key    The config key to write to
	 * @param array       $config The serialized configuration to write
	 * @return boolean
	 */
	final protected static function insert($group, $key, $config)
	{
		DB::table('config')->insert([
			'group' => $group,
			'key' => $key,
			'value' => $config
		]);
	}

	/**
	 * Update the config values in the table
	 *
	 * @param string      $group  The config group
	 * @param string      $key    The config key to write to
	 * @param array       $config The serialized configuration to write
	 * @return boolean
	 */
	final protected static function update($group, $key, $config)
	{
		DB::table('config')
			->where('group', '=', $group)
			->where('key', '=', $key)
			->update([
				'value' => $config
			]);
	}
}