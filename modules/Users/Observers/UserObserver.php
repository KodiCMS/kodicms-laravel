<?php namespace KodiCMS\Users\Observers;

/**
 * TODO: добавить логирование событий
 * Class UserObserver
 * @package KodiCMS\Users\Observers
 */
class UserObserver {

	/**
	 * @param \KodiCMS\Users\Model\User $model
	 * @return bool
	 */
	public function created($model)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Users\Model\User $model
	 * @return bool
	 */
	public function updated($model)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Users\Model\User $model
	 * @return bool
	 */
	public function deleting($model)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Users\Model\User $model
	 * @return bool
	 */
	public function deleted($model)
	{

	}
}