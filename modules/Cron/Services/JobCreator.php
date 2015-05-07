<?php namespace KodiCMS\Cron\Services;

use KodiCMS\CMS\Contracts\ModelCreator;
use KodiCMS\Cron\Model\Job;
use Validator;

class JobCreator implements ModelCreator
{

	public function validator(array $data)
	{
		return Validator::make($data, [
			'date_start' => 'required|date',
			'date_end'   => 'required|date',
			'task_name'  => 'required',
			'name'       => 'required',
			'interval'   => 'integer|min:1|required_without:crontime',
			'crontime'   => 'crontab|required_without:interval',
		], trans('cron::validation'));
	}

	public function create(array $data)
	{
		return Job::create($data);
	}

}
