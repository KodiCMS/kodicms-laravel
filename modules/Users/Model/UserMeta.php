<?php namespace KodiCMS\Users\Model;

use Illuminate\Support\Facades\Auth;

class UserMeta
{
	/**
	 * @var array
	 */
	protected static $cache = [];

	/**
	 * @param string $key
	 * @param mixed $default
	 * @param integer|User $userId
	 * @return mixed
	 */
	public static function get($key, $default = NULL, $userId = NULL)
	{
		$userId = static::getUser($userId);
		static::load($userId);

		$value = static::getFromCache($userId, $key);

		if (is_null($value)) {
			if ($userId === -1) {
				return $default;
			}

			static::load(-1);
			$value = static::getFromCache(-1, $key);

			if (is_null($value)) {
				return $default;
			}
		}

		return $value;
	}

	/**
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param integer|User $userId
	 * @return boolean
	 */
	public static function set($key, $value, $userId = NULL)
	{
		$userId = static::getUser($userId);
		static::load($userId);
		$value = json_encode($value);

		if (isset(static::$cache[$userId][$key])) {
			$status = (bool)\DB::table('user_meta')
				->where('key', $key)
				->where('user_id', $userId)
				->update(['value' => $value]);
		} else {
			$status = (bool)\DB::table('user_meta')->insert([
				'key' => $key,
				'value' => $value,
				'user_id' => $userId
			]);
		}

		static::clearCache($userId);

		return $status;
	}

	/**
	 *
	 * @param string $key
	 * @param integer|User $userId
	 * @return boolean
	 */
	public static function delete($key, $userId = NULL)
	{
		$userId = static::getUser($userId);
		static::clearCache($userId);

		return (bool)\DB::table('user_meta')
			->where('user_id', $userId)
			->where('key', $key)
			->delete();
	}

	/**
	 *
	 * @param integer|User $userId
	 * @return boolean
	 */
	public static function clear($userId = NULL)
	{
		$userId = static::getUser($userId);
		static::clearCache($userId);

		return (bool) DB::table('user_meta')
			->where('user_id', $userId)
			->delete();
	}

	/**
	 * TODO: добавить кеширование
	 * @param integer|User $userId
	 * @return array
	 */
	protected static function load($userId = NULL)
	{
		$userId = static::getUser($userId);

		if (!isset(static::$cache[$userId])) {
			static::$cache[$userId] = \DB::table('user_meta')
				->select('key', 'value')
				->where('user_id', $userId)
				->get()
				->liast('value', 'key');
		}

		return static::$cache[$userId];
	}

	/**
	 * @param integer|User $userId
	 * @param string $key
	 * @return mixed|null
	 */
	protected static function getFromCache($userId, $key)
	{
		$value = array_get(static::$cache, $userId . '.' . $key)
		if (!is_null($value)) {
			return json_decode($value);
		}

		return NULL;
	}

	/**
	 * TODO: добавить удаление из кеша БД
	 * @param integer|User $userId
	 */
	protected static function clearCache($userId = NULL)
	{
		$userId = static::getUser($userId);
		unset(static::$cache[$userId]);
	}

	/**
	 * @param integer|User $userId
	 * @return integer
	 */
	protected static function getUser($userId = NULL)
	{
		if ($userId === NULL) {
			return Auth::user()->id;
		}

		if($userId instanceof User)
		{
			return $userId->id;
		}

		return $userId;
	}
}