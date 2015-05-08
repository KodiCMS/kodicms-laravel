<?php namespace KodiCMS\Email\Services;

use KodiCMS\CMS\Contracts\ModelCreator;
use KodiCMS\Email\Model\EmailType;
use Validator;

class EmailTypeCreator implements ModelCreator
{

	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required',
			'code' => 'required',
		]);
	}

	public function create(array $data)
	{
		return EmailType::create($data);
	}

}
