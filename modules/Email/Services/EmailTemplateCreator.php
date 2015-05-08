<?php namespace KodiCMS\Email\Services;

use KodiCMS\CMS\Contracts\ModelCreator;
use KodiCMS\Email\Model\EmailTemplate;
use Validator;

class EmailTemplateCreator implements ModelCreator
{

	public function validator(array $data)
	{
		return Validator::make($data, [
			'status' => 'required|boolean',
			'use_queue' => 'required|boolean',
			'email_from' => 'required',
			'email_to' => 'required',
			'subject' => 'required',
			'message' => 'required',
			'email_type_id' => 'required|exists:email_types,id',
		]);
	}

	public function create(array $data)
	{
		return EmailTemplate::create($data);
	}

}
