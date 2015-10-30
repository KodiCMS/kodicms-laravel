<?php
namespace KodiCMS\Users\Model;

use DB;
use Illuminate\Support\Facades\Auth;

class UserMeta
{

    const TABLE = 'user_meta';

    /**
     * @var array
     */
    protected static $cache = [];


    /**
     * @param string       $key
     * @param mixed        $default
     * @param integer|User $userId
     *
     * @return mixed
     */
    public static function get($key, $default = null, $userId = null)
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
     * @param string       $key
     * @param mixed        $value
     * @param integer|User $userId
     *
     * @return boolean
     */
    public static function set($key, $value, $userId = null)
    {
        $userId = static::getUser($userId);
        static::load($userId);
        $value = json_encode($value);

        if (isset( static::$cache[$userId][$key] )) {
            $status = (bool) DB::table(static::TABLE)
                ->where('key', $key)
                ->where('user_id', $userId)
                ->update(['value' => $value]);
        } else {
            $status = (bool) DB::table(static::TABLE)
                ->insert([
                    'key'     => $key,
                    'value'   => $value,
                    'user_id' => $userId,
                ]);
        }

        static::clearCache($userId);

        return $status;
    }


    /**
     * @param string       $key
     * @param integer|User $userId
     *
     * @return boolean
     */
    public static function delete($key, $userId = null)
    {
        $userId = static::getUser($userId);
        static::clearCache($userId);

        return (bool) DB::table(static::TABLE)
            ->where('user_id', $userId)
            ->where('key', $key)
            ->delete();
    }


    /**
     * TODO: используется не объявленный параметр
     * @param string|array $key
     *
     * @return boolean
     */
    public static function clearByKey($key)
    {
        $userId = static::getUser($userId);
        static::clearCache($userId);

        return (bool) DB::table(static::TABLE)
            ->whereIn('key', (array) $key)
            ->delete();
    }


    /**
     * @param integer|User $userId
     *
     * @return boolean
     */
    public static function clear($userId = null)
    {
        $userId = static::getUser($userId);
        static::clearCache($userId);

        return (bool) DB::table(static::TABLE)
            ->where('user_id', $userId)
            ->delete();
    }


    /**
     * @param integer|User $userId
     *
     * @return array
     */
    protected static function load($userId = null)
    {
        $userId = static::getUser($userId);

        if ( ! isset( static::$cache[$userId] )) {
            static::$cache[$userId] = DB::table(static::TABLE)
                ->select('key', 'value')
                ->where('user_id', $userId)
                ->lists('value', 'key');
        }

        return static::$cache[$userId];
    }


    /**
     * @param integer|User $userId
     * @param string       $key
     *
     * @return mixed|null
     */
    protected static function getFromCache($userId, $key)
    {
        $value = array_get(static::$cache, $userId . '.' . $key);
        if ( ! is_null($value)) {
            return json_decode($value, true);
        }

        return null;
    }


    /**
     * @param integer|User $userId
     */
    protected static function clearCache($userId = null)
    {
        $userId = static::getUser($userId);
        unset( static::$cache[$userId] );
    }


    /**
     * @param integer|User $userId
     *
     * @return integer
     */
    protected static function getUser($userId = null)
    {
        if ($userId === null) {
            return Auth::user()->id;
        }

        if ($userId instanceof User) {
            return $userId->id;
        }

        return $userId;
    }
}