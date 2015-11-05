<?php

namespace KodiCMS\Support\Cache;

use Closure;
use Illuminate\Cache\TaggableStore;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Database\ConnectionInterface;

class SqLiteTaggedStore extends TaggableStore implements Store
{
    /**
     * The database connection instance.
     *
     * @var \Illuminate\Database\ConnectionInterface
     */
    protected $connection;

    /**
     * @var string
     */
    protected $table = 'caches';

    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    protected $prefix;

    /**
     * @param ConnectionInterface $connection
     * @param atring              $schema
     * @param string              $prefix
     */
    public function __construct(ConnectionInterface $connection, $schema, $prefix = '')
    {
        $this->connection = $connection;
        $this->prefix = $prefix;

        $result = $this->connection->select("SELECT * FROM sqlite_master WHERE name = '{$this->table}' AND type = 'table'");
        if (0 == count($result)) {
            // Create the caches table
            $this->connection->statement($schema);
        }
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $prefixed = $this->prefix.$key;

        $cache = $this->table()->where('key', '=', $prefixed)->first();

        // If we have a cache record we will check the expiration time against current
        // time on the system and see if the record has expired. If it has, we will
        // remove the records from the database table so it isn't returned again.
        if (! is_null($cache)) {
            if (is_array($cache)) {
                $cache = (object) $cache;
            }

            if (time() >= $cache->expiration) {
                $this->forget($key);

                return;
            }

            return unserialize($cache->value);
        }
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string $key
     * @param  mixed  $value
     * @param  int    $minutes
     *
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        return $this->tags(null)->put($key, $value, $minutes);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function increment($key, $value = 1)
    {
        $this->connection->transaction(function () use ($key, $value) {
            return $this->incrementOrDecrement($key, $value, function ($current) use ($value) {
                return $current + $value;
            });
        });
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function decrement($key, $value = 1)
    {
        $this->connection->transaction(function () use ($key, $value) {
            return $this->incrementOrDecrement($key, $value, function ($current) use ($value) {
                return $current - $value;
            });
        });
    }

    /**
     * Increment or decrement an item in the cache.
     *
     * @param  string   $key
     * @param  mixed    $value
     * @param  \Closure $callback
     *
     * @return void
     */
    protected function incrementOrDecrement($key, $value, Closure $callback)
    {
        $prefixed = $this->prefix.$key;

        $cache = $this->table()->where('key', $prefixed)->lockForUpdate()->first();

        if (! is_null($cache)) {
            $current = $cache->value;

            if (is_numeric($current)) {
                $this->table()->where('key', $prefixed)->update([
                    'value' => serialize($callback($current)),
                ]);
            }
        }
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function forever($key, $value)
    {
        $this->put($key, $value, 5256000);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string $key
     *
     * @return bool
     */
    public function forget($key)
    {
        $this->table()->where('key', '=', $this->prefix.$key)->delete();

        return true;
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush()
    {
        $this->table()->delete();
    }

    /**
     * Get a query builder for the cache table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table()
    {
        return $this->connection->table($this->table);
    }

    /**
     * Get the underlying database connection.
     *
     * @return \Illuminate\Database\ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Begin executing a new tags operation.
     *
     * @param  array|mixed $names
     *
     * @return \Illuminate\Cache\TaggedCache
     */
    public function tags($names)
    {
        return new DatabaseTaggedCache($this, is_array($names)
            ? $names
            : func_get_args());
    }
}
