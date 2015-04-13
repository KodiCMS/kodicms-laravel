<?php namespace KodiCMS\Pages;

use Carbon\Carbon;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\PagePart as PagePartModel;
use View;

class PagePart
{
	/**
	 *
	 * @var array
	 */
	protected static $cached = [];

	/**
	 * @param FrontendPage $page
	 * @param string $part
	 * @param boolean $inherit
	 * @return boolean
	 */
	public static function exists(FrontendPage $page, $part, $inherit = false)
	{
		static::loadPartsbyPageId($page->getId());

		if (isset(static::$cached[$page->getId()][$part]))
		{
			return true;
		}
		else if ($inherit !== false AND ($parent = $page->getParent()) instanceof FrontendPage)
		{
			return static::exists($parent, $part, true);
		}

		return false;
	}

	/**
	 * @param FrontendPage $page
	 * @param string $part
	 * @param boolean $inherit
	 * @return string
	 */
	public static function getContent(FrontendPage $page, $part = 'body', $inherit = false)
	{
		if (static::exists($page, $part))
		{
			return static::get($page->getId(), $part);
		}
		else if ($inherit !== false AND ($parent = $page->getParent()) instanceof FrontendPage)
		{
			return static::getContent($parent, $part, true);

		}
	}

	/**
	 * @param FrontendPage|integer $page
	 * @param string $part
	 * @return string
	 */
	public static function get($page, $part)
	{
		$html = null;

		$pageId = ($page instanceof FrontendPage) ? $page->getId() : (int)$page;

		static::loadPartsbyPageId($pageId);

		if (empty(static::$cached[$pageId][$part]))
		{
			return null;
		}

		if (($part = static::$cached[$pageId][$part]) instanceof PagePartModel)
		{
			$html = $part->content_html;
		}
		else if (($view = static::$cached[$pageId][$part]) instanceof View)
		{
			$html = (string)$view;
		}

		return $html;
	}

	/**
	 * @param integer $pageId
	 * @return array|null
	 */
	final private static function loadPartsbyPageId($pageId)
	{
		if (!array_key_exists($pageId, static::$cached))
		{
			self::$cached[$pageId] = Cache::tags(PagePartModel::table())->remember("pageParts::{$pageId}", Carbon::now()->addHour(1), function () use ($pageId)
			{
				return PagePartModel::select('name', 'content', 'content_html')->where('page_id', $pageId)->get();
			});
		}

		return self::$cached[$pageId];
	}
}