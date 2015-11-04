<?php

namespace KodiCMS\CMS;

class CMS
{
    const VERSION = '0.3.1 beta';
    const NAME = 'KodiCMS';
    const WEBSITE = 'http://kodicms.ru';

    const CMS_PREFIX = 'cms';

    /**
     * @return bool
     */
    public function isInstalled()
    {
        return is_file(base_path(app()->environmentFile()));
    }

    /**
     * @return string
     */
    public function backendUrlSegment()
    {
        return config('cms.backend_url_segment', 'backend');
    }

    /**
     * @param null|string $path
     *
     * @return string
     */
    public function backendUrl($path = null)
    {
        return url($this->backendUrlSegment().$this->trimPath($path));
    }

    /**
     * @param null|string $path
     *
     * @return string
     */
    public function resourcesUrl($path = null)
    {
        return url(static::CMS_PREFIX.$this->trimPath($path));
    }

    /**
     * @param null|string $path
     *
     * @return string
     */
    public function backendResourcesPath($path = null)
    {
        return public_path(static::CMS_PREFIX.DIRECTORY_SEPARATOR.(! is_null($path) ? normalize_path($path) : $path));
    }

    /**
     * @param null|string $path
     *
     * @return string
     */
    public function backendResourcesUrl($path = null)
    {
        return $this->backendUrl(static::CMS_PREFIX.$this->trimPath($path));
    }

    /**
     * @param string $path
     * @param string $separator
     *
     * @return string
     */
    protected function trimPath($path, $separator = '/')
    {
        return ! is_null($path) ? $separator.ltrim($path, $separator) : $path;
    }
}
