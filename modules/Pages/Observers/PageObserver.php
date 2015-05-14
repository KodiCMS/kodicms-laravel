<?php namespace KodiCMS\Pages\Observers;

use KodiCMS\Pages\Model\PagePart;
use Request;
use Cache;
use KodiCMS\Pages\Model\Page;

class PageObserver
{
	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return void
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
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return void
	 */
	public function creating($page)
	{
		$user = auth()->user();
		if (!is_null($user))
		{
			$page->created_by_id = $user->id;
		}
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return void
	 */
	public function created($page)
	{

	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return void
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
	 * @return void
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
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return void
	 */
	public function reordering($page)
	{

	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return void
	 */
	public function reordered($page)
	{

	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return void
	 */
	public function deleting($page)
	{

	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return void
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