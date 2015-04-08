<?php namespace KodiCMS\Pages\Observers;
use KodiCMS\Pages\Model\Page;

/**
 * TODO: добавить логирование событий
 *
 * Class RoleObserver
 * @package KodiCMS\Users\Observers
 */
class PageObserver
{
	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function creating($page)
	{
		$user = auth()->user();
		if (!is_null($user)) {
			$page->created_by_id = $user->id;
		}

		return TRUE;
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function created($page)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Pages\Model\Page $page
	 * @return bool
	 */
	public function updating($page)
	{
		$user = auth()->user();
		if (!is_null($user)) {
			$page->updated_by_id = $user->id;
		}

		return TRUE;
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

		return TRUE;
	}

}