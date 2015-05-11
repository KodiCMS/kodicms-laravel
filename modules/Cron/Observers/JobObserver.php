<?php namespace KodiCMS\Cron\Observers;

class JobObserver
{
	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return bool
	 */
	public function saving($job)
	{
		if ($job->isDirty('interval', 'crontime', 'last_run'))
		{
			$job->setNextRun();
		}

		return true;
	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return bool
	 */
	public function creating($job)
	{
		$job->status = Job::STATUS_NEW;

		return true;
	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return bool
	 */
	public function created($job)
	{
		return true;
	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return bool
	 */
	public function updating($job)
	{
		return true;
	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return bool
	 */
	public function deleting($job)
	{
		return true;
	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return bool
	 */
	public function deleted($job)
	{
		return true;
	}
}