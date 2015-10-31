<?php
namespace KodiCMS\Widgets\Contracts;

interface WidgetCacheable extends WidgetRenderable
{

    /**
     * @return bool
     */
    public function isCacheEnabled();


    /**
     * @return int
     */
    public function getCacheLifetime();


    /**
     * @return array
     */
    public function getCacheTags();


    /**
     * @return string
     */
    public function getCacheTagsAsString();


    /**
     * @return string
     */
    public function getCacheKey();


    /**
     * @return boolean
     */
    public function clearCache();


    /**
     * @return boolean
     */
    public function clearCacheByTags();
}