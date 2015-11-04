<?php

namespace KodiCMS\Pages;

use Carbon\Carbon;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\PagePart as PagePartModel;
use Cache;

class PagePart
{
    /**
     * @var array
     */
    protected static $cached = [];

    /**
     * @param FrontendPage $page
     * @param string       $part
     * @param bool      $inherit
     *
     * @return bool
     */
    public static function exists(FrontendPage $page, $part, $inherit = false)
    {
        $parts = static::loadPartsbyPageId($page->getId());

        if (isset($parts[$page->getId()][$part])) {
            return $parts[$page->getId()][$part];
        } elseif ($inherit !== false and ($parent = $page->getParent()) instanceof FrontendPage) {
            return static::exists($parent, $part, true);
        }

        return false;
    }

    /**
     * @param FrontendPage $page
     * @param string       $part
     * @param bool      $inherit
     *
     * @return string|null
     */
    public static function getContent(FrontendPage $page, $part = 'body', $inherit = false)
    {
        if (static::exists($page, $part)) {
            return static::get($page->getId(), $part);
        } elseif ($inherit !== false and ($parent = $page->getParent()) instanceof FrontendPage) {
            return static::getContent($parent, $part, true);
        }

        return;
    }

    /**
     * @param FrontendPage|int $page
     * @param string               $part
     *
     * @return string
     */
    public static function get($page, $part)
    {
        $html = null;

        $pageId = ($page instanceof FrontendPage) ? $page->getId() : (int) $page;

        $parts = static::loadPartsByPageId($pageId);

        if (empty($parts[$pageId][$part])) {
            return;
        }

        return array_get($parts, implode('.', [$pageId, $part, 'content_html']));
    }

    /**
     * TODO: добавить кеширование на основе тегов.
     *
     * @param int $pageId
     *
     * @return array|null
     */
    final private static function loadPartsByPageId($pageId)
    {
        if (! array_key_exists($pageId, static::$cached)) {
            self::$cached[$pageId] = Cache::remember("pageParts::{$pageId}", Carbon::now()->addHour(1), function () use (
                $pageId
            ) {
                $parts = PagePartModel::select('id', 'name', 'content', 'content_html')->where('page_id', $pageId)->get();

                $return = [];
                foreach ($parts as $part) {
                    $return[$pageId][$part->name] = $part->toArray();
                }

                return $return;
            });
        }

        return self::$cached[$pageId];
    }
}
