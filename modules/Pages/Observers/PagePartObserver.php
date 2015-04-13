<?php namespace KodiCMS\Pages\Observers;

use KodiCMS\CMS\Helpers\WYSIWYG;
use KodiCMS\Pages\Model\PagePart;

class PagePartObserver
{
	/**
	 * @param \KodiCMS\Pages\Model\PagePart $part
	 * @return bool
	 */
	public function saving($part)
	{
		if (is_null($part->wysiwyg))
		{
			$part->wysiwyg = config('cms.wysiwyg.default_html_editor');
		}

		if (is_null($part->is_protected))
		{
			$part->is_protected = PagePart::PART_NOT_PROTECTED;
		}

		if (is_null($part->name))
		{
			$part->name = 'part';
		}

		if (!is_null($part->wysiwyg))
		{
			$filter = WYSIWYG::getFilter($part->wysiwyg);
			$part->content_html = $filter->apply($part->content);
		}

		return TRUE;
	}

	/**
	 * TODO: очищать кеш закешированых частей страниц
	 * @param \KodiCMS\Pages\Model\PagePart $part
	 */
	public function saved($part)
	{

	}

	/**
	 * TODO: очищать кеш закешированых частей страниц
	 * @param \KodiCMS\Pages\Model\PagePart $part
	 */
	public function deleted($part)
	{

	}
}