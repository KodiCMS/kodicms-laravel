<?php namespace KodiCMS\Pages\Observers;

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
		return TRUE;
	}

}