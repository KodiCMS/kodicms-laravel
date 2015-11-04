<?php

namespace KodiCMS\Widgets\Traits;

use Cache;
use Illuminate\Cache\TaggableStore;

trait WidgetCache
{
    use WidgetRender;

    /**
     * @return bool
     */
    public function isCacheEnabled()
    {
        return (bool) $this->cache;
    }

    /**
     * @return int
     */
    public function getCacheLifetime()
    {
        return (int) $this->getSetting('cache_lifetime', 0);
    }

    /**
     * @return array
     */
    public function getHTMLSelectCacheTags()
    {
        return array_combine($this->getCacheTags(), $this->getCacheTags());
    }

    /**
     * @return array
     */
    public function getCacheTags()
    {
        return $this->getSetting('cache_tags', []);
    }

    /**
     * @return string
     */
    public function getCacheTagsAsString()
    {
        return implode(', ', $this->getCacheTags());
    }

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return 'Widget::'.$this->getType().'::'.$this->getId();
    }

    /**
     * @param bool  $enabled
     * @param int   $lifetime
     * @param array $tags
     */
    public function setCacheSettings($enabled = true, $lifetime = 300, array $tags = [])
    {
        $this->cache = $enabled;
        $this->cache_lifetime = $lifetime;
        $this->cache_tags = $tags;
    }

    /**
     * @param bool $status
     */
    public function setSettingCache($status)
    {
        $this->settings['cache'] = (bool) $status;
    }

    /**
     * @param int $lifetime
     */
    public function setSettingCacheLifetime($lifetime)
    {
        $this->settings['cache_lifetime'] = (int) $lifetime;
    }

    /**
     * @param array | string $tags
     */
    public function setSettingCacheTags(array $tags)
    {
        $this->settings['cache_tags'] = array_unique($tags);
    }

    /**
     * @return bool
     */
    public function clearCache()
    {
        Cache::forget($this->getCacheKey());
    }

    /**
     * @return bool
     */
    public function clearCacheByTags()
    {
        if (Cache::getFacadeRoot()->store()->getStore() instanceof TaggableStore) {
            Cache::tags($this->getCacheTags())->flush();
        }
    }
}
