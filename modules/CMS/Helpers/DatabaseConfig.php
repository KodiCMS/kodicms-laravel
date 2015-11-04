<?php

namespace KodiCMS\CMS\Helpers;

use DB;
use Cache;

class DatabaseConfig
{
    /**
     * @var string
     */
    protected $cacheKey = 'databaseConfig';

    /**
     * @var array
     */
    protected $config = [];

    public function __construct()
    {
        $databaseConfig = Cache::rememberForever($this->cacheKey, function () {
            return DB::table('config')->get();
        });

        foreach ($databaseConfig as $row) {
            $this->config[$row->group][$row->key] = json_decode($row->value, true);
        }
    }

    /**
     * @return array
     */
    final public function getAll()
    {
        return $this->config;
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    final public function get($key, $default = null)
    {
        return array_get($this->config, $key, $default);
    }

    /**
     * @param string $group
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    final public function set($group, $key, $value)
    {
        $value = json_encode($value);

        if (isset($this->config[$group][$key])) {
            $this->update($group, $key, $value);
        } else {
            $this->insert($group, $key, $value);
        }

        Cache::forget($this->cacheKey);

        return true;
    }

    /**
     * @param array $settings
     */
    final public function save(array $settings)
    {
        foreach ($settings as $group => $values) {
            if (is_array($values)) {
                foreach ($values as $key => $value) {
                    $this->set($group, $key, $value);
                }
            } else {
                $this->set('site', $group, $values);
            }
        }
    }

    /**
     * Insert the config values into the table.
     *
     * @param string $group  The config group
     * @param string $key    The config key to write to
     * @param array  $config The serialized configuration to write
     *
     * @return bool
     */
    final protected function insert($group, $key, $config)
    {
        DB::table('config')->insert([
            'group' => $group,
            'key'   => $key,
            'value' => $config,
        ]);
    }

    /**
     * Update the config values in the table.
     *
     * @param string $group  The config group
     * @param string $key    The config key to write to
     * @param array  $config The serialized configuration to write
     *
     * @return bool
     */
    final protected function update($group, $key, $config)
    {
        return DB::table('config')
            ->where('group', '=', $group)
            ->where('key', '=', $key)->update([
                'value' => $config,
            ]);
    }
}
