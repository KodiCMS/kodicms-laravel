<?php namespace KodiCMS\Cron\Services;

use KodiCMS\CMS\Contracts\ModelUpdator;
use KodiCMS\Cron\Model\Job;
use Validator;

class JobUpdator implements ModelUpdator
{

	/**
	 * @param int $id
	 * @param array $data
	 * @return Validator
	 */
	public function validator($id, array $data)
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

	/**
	 * @param int $id
	 * @param array $data
	 * @return Job
	 */
	public function update($id, array $data)
	{
		$job = Job::findOrFail($id);
		$job->update($data);
		return $job;
	}

}
