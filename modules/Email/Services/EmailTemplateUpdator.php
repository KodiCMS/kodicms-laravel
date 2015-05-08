<?php namespace KodiCMS\Email\Services;

use KodiCMS\CMS\Contracts\ModelUpdator;
use KodiCMS\Email\Model\EmailTemplate;
use Validator;

class EmailTemplateUpdator implements ModelUpdator
{

	public function validator($id, array $data)
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

	public function update($id, array $data)
	{
		$job = EmailTemplate::findOrFail($id);
		$job->update($data);
		return $job;
	}

}
