<?php

namespace KodiCMS\Support\Cache;

use Closure;
use Illuminate\Contracts\Cache\Store;

class DatabaseTaggedCache implements Store
{
    /**
     * The cache store implementation.
     *
     * @var \Illuminate\Contracts\Cache\Store
     */
    protected $store;

    /**
     * @array
     */
    protected $tags;

    /**
     * Create a new tagged cache instance.
     *
     * @param  SqLiteTaggedStore $store
     * @param  atrray            $tags
     *
     * @return void
     */
    public function __construct(SqLiteTaggedStore $store, array $tags)
    {
        $this->tags = $tags;
        $this->store = $store;
    }

    /**
     * Determine if an item exists in the cache.
     *
     * @param  string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return ! is_null($this->get($key));
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = $this->store->get($key);

        return ! is_null($value) ? $value : value($default);
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string        $key
     * @param  mixed         $value
     * @param  \DateTime|int $minutes
     *
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        $key = $this->taggedItemKey($key);

        $expiration = $this->getTime() + ($minutes * 60);

        $value = serialize($value);

        $tags = $this->prepareTags();

        try {
            $this->table()->insert(compact('key', 'tags', 'expiration', 'value'));
        } catch (\Exception $e) {
            $this->table()->where('key', '=', $key)->update(compact('tags', 'expiration', 'value'));
        }
    }

    /**
     * Store an item in the cache if the key does not exist.
     *
     * @param  string        $key
     * @param  mixed         $value
     * @param  \DateTime|int $minutes
     *
     * @return bool
     */
    public function add($key, $value, $minutes)
    {
        if (! $this->has($key)) {
            $this->put($key, $value, $minutes);

            return true;
        }

        return false;
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
        $this->store->increment($key, $value);
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
        $this->store->decrement($key, $value);
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
        $this->store->forever($key, $value);
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
        return $this->store->forget($key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return void
     */
    public function flush()
    {
        foreach ($this->tags as $tag) {
            $this->table()->where('tags', 'like', "%<{$tag}>%")->delete();
        }
    }

    /**
     * Get an item from the cache, or store the default value.
     *
     * @param  string        $key
     * @param  \DateTime|int $minutes
     * @param  \Closure      $callback
     *
     * @return mixed
     */
    public function remember($key, $minutes, Closure $callback)
    {
        // If the item exists in the cache we will just return this immediately
        // otherwise we will execute the given Closure and cache the result
        // of that execution for the given number of minutes in storage.
        if (! is_null($value = $this->get($key))) {
            return $value;
        }

        $this->put($key, $value = $callback(), $minutes);

        return $value;
    }

    /**
     * Get an item from the cache, or store the default value forever.
     *
     * @param  string   $key
     * @param  \Closure $callback
     *
     * @return mixed
     */
    public function sear($key, Closure $callback)
    {
        return $this->rememberForever($key, $callback);
    }

    /**
     * Get an item from the cache, or store the default value forever.
     *
     * @param  string   $key
     * @param  \Closure $callback
     *
     * @return mixed
     */
    public function rememberForever($key, Closure $callback)
    {
        // If the item exists in the cache we will just return this immediately
        // otherwise we will execute the given Closure and cache the result
        // of that execution for the given number of minutes. It's easy.
        if (! is_null($value = $this->get($key))) {
            return $value;
        }

        $this->forever($key, $value = $callback());

        return $value;
    }

    /**
     * @return string
     */
    public function prepareTags()
    {
        return '<'.implode('>,<', $this->tags).'>';
    }

    /**
     * Get a fully qualified key for a tagged item.
     *
     * @param  string $key
     *
     * @return string
     */
    public function taggedItemKey($key)
    {
        return $this->getPrefix().$key;
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->store->getPrefix();
    }

    /**
     * Get the current system time.
     *
     * @return int
     */
    protected function getTime()
    {
        return time();
    }

    /**
     * Get a query builder for the cache table.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table()
    {
        return $this->store->getConnection()->table($this->store->getTable());
    }
}
