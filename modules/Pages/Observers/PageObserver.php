<?php namespace KodiCMS\Pages\Observers;

use Cache;
use KodiCMS\Pages\Model\PageBehaviorSettings;
use Request;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PagePart;

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
	public function saved($page)
	{
		if ($page->hasBehavior())
		{
			$settings = [
				'settings' => Request::input('behavior_settings', [])
			];
			$behaviorSettings = $page->behaviorSettings()->first();
			if (is_null($behaviorSettings))
			{
				$page->behaviorSettings()->save(new PageBehaviorSettings($settings));
			}
			else
			{
				$behaviorSettings->update($settings);
			}
		}
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return void
	 */
	public function creating($page)
	{
		if (!is_null($user = auth()->user()))
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
		if (!is_null($user = auth()->user()))
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

		$page->behaviorSettings()->delete();

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