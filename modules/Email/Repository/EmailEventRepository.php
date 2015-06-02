<?php namespace KodiCMS\Email\Repository;

use KodiCMS\CMS\Repository\BaseRepository;
use KodiCMS\Email\Model\EmailEvent;

class EmailEventRepository extends BaseRepository
{
	/**
	 * @param EmailEvent $model
	 */
	function __construct(EmailEvent $model)
	{
		parent::__construct($model);
	}

	/**
	 * @param array $data
	 * @return \Illuminate\Validation\Validator
	 */
	public function validatorOnCreate(array $data = [])
	{
		return $this->validator($data, [
			'name' => 'required',
			'code' => 'required',
		]);
	}

	/**
	 * @param array $data
	 * @return \Illuminate\Validation\Validator
	 */
	public function validatorOnUpdate(array $data = [])
	{
		return $this->validator($data, [
			'name' => 'required',
		]);
	}

	/**
	 * @return array
	 */
	public function eventsList()
	{
		return $this->all()->lists('fullName', 'id');
	}
}