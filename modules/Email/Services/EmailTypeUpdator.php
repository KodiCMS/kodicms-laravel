<?php namespace KodiCMS\Email\Services;

use KodiCMS\CMS\Contracts\ModelUpdator;
use KodiCMS\Email\Model\EmailType;
use Validator;

class EmailTypeUpdator implements ModelUpdator
{

	public function validator($id, array $data)
	{
		return Validator::make($data, [
			'name' => 'required',
		]);
	}

	public function update($id, array $data)
	{
		$job = EmailType::findOrFail($id);
		$job->update($data);
		return $job;
	}

}
