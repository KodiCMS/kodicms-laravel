<?php namespace KodiCMS\Users\Observers;

/**
 * TODO: добавить логирование событий
 * Class UserObserver
 * @package KodiCMS\Users\Observers
 */
class UserObserver {

	/**
	 * @param \KodiCMS\Users\Model\User $model
	 * @return void
	 */
	public function created($model)
	{

	}

	/**
	 * @param \KodiCMS\Users\Model\User $model
	 * @return void
	 */
	public function updated($model)
	{

	}

	/**
	 * @param \KodiCMS\Users\Model\User $model
	 * @return void
	 */
	public function deleting($model)
	{

	}

	/**
	 * @param \KodiCMS\Users\Model\User $model
	 * @return bool
	 */
	public function deleted($model)
	{
		// Удаление связанных ролей
		$model->roles()->sync([]);
	}
}