<?php namespace KodiCMS\Email\Repository;

use KodiCMS\CMS\Repository\BaseRepository;
use KodiCMS\Email\Model\EmailEvent;

class EmailEventRepository extends BaseRepository
{

	function __construct(EmailEvent $model)
	{
		parent::__construct($model);
	}

	public function validatorOnCreate($data = [])
	{
		return $this->validator($data, [
			'name' => 'required',
			'code' => 'required',
		]);
	}

	public function validatorOnUpdate($data = [])
	{
		return $this->validator($data, [
			'name' => 'required',
		]);
	}

	public function eventsList()
	{
		return $this->all()->lists('fullName', 'id');
	}

}