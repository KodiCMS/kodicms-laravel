<?php namespace KodiCMS\Pages\Observers;

use KodiCMS\Pages\Model\PagePart;
use Request;
use Cache;
use KodiCMS\Pages\Model\Page;

/**
 * TODO: добавить логирование событий
 */
class PageObserver
{
	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function saving($page)
	{
		if ($page->exists)
		{
			$this->updateParts($page);
		}

		if ($page->behavior == '')
		{
			$page->behavior = null;
		}

		if (is_null($page->redirect_url))
		{
			$page->is_redirect = false;
		}

		if (!$page->is_redirect)
		{
			$page->redirect_url = null;
		}

		$this->clearCache($page);

		return true;
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function creating($page)
	{
		$user = auth()->user();
		if (!is_null($user))
		{
			$page->created_by_id = $user->id;
		}

		return true;
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function created($page)
	{

	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function updating($page)
	{
		$user = auth()->user();
		if (!is_null($user))
		{
			$page->updated_by_id = $user->id;
		}
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function updateParts($page)
	{
		$partContent = Request::input('part_content', []);

		foreach ($partContent as $id => $content)
		{
			$part = PagePart::find($id);
			if (is_null($part)) continue;

			if ($content == $part->content)
			{
				continue;
			}

			$part->update(['content' => $content]);
		}

		return true;
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function reordering($page)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function reordered($page)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function deleting($page)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function deleted($page)
	{
		// Все дочерние страницы перекидываем в корень
		// TODO: спрашивать у пользователя нужно ли удалять все внутренние страницы
		//Page::where('parent_id', $page->id)->delete();
		Page::where('parent_id', $page->id)->update([
			'parent_id' => 1
		]);

		$this->clearCache($page);

		return TRUE;
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 */
	protected function clearCache($page)
	{
		Cache::forget("id::{$page->id}::TRUE");
		Cache::forget("slug::{$page->slug}::TRUE{$page->parent_id}");
	}
}