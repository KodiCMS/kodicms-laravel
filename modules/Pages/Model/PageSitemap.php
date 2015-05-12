<?php namespace KodiCMS\Pages\Model;

use KodiCMS\CMS\Helpers\Sitemap;
use KodiCMS\CMS\Helpers\URL;

class PageSitemap {
	
	/**
	 * Хранение карт сайта с разынми параметрами
	 * @var array 
	 */
	protected static $sitemap = [];
	
	/**
	 * Получение карты сайта
	 * 
	 * @param boolean $includeHidden Включить скрытые страницы
	 * @return PageSitemap
	 */
	public static function get($includeHidden = FALSE)
	{
		$status = (bool) $includeHidden ? 1 : 0;

		if (!array_key_exists($status, static::$sitemap))
		{
			$query = Page::orderBy('parent_id', 'asc')
				->orderBy('position', 'asc');

			if ((bool) $includeHidden === FALSE)
			{
				$query->whereIn('status', [FrontendPage::STATUS_PUBLISHED]);
			}

			$pages = [];

			foreach ($query->get() as $page)
			{
				$pages[$page->id] = $page->toArray();
				$pages[$page->id]['uri'] = '';
				$pages[$page->id]['url'] = '';
				$pages[$page->id]['slug'] = $page->slug;
				$pages[$page->id]['level'] = 0;
				$pages[$page->id]['is_active'] = TRUE;
			}

			$structuredPages = [];
			foreach ($pages as & $page)
			{
				$structuredPages[$page['parent_id']][] = & $page;
			}

			foreach ($pages as & $page)
			{
				if (isset($structuredPages[$page['id']]))
				{
					foreach ($structuredPages[$page['id']] as & $_page)
					{
						$_page['level'] = $page['level'] + 1;
						$_page['parent'] = $page;

						$_page['uri'] = $page['uri'] . '/' . $_page['slug'];
						$_page['url'] = url($_page['uri']);
						$_page['is_active'] = URL::match($_page['uri']);

						if (empty($_page['layout_file']))
						{
							$_page['layout_file'] = $page['layout_file'];
						}

						if ($_page['is_active'])
						{
							$page['is_active'] = TRUE;
						}
					}

					$page['childs'] = $structuredPages[$page['id']];
				}
			}

			static::$sitemap[$status] = new Sitemap(reset($structuredPages));
		}

		return clone(static::$sitemap[$status]);
	}
}