<?php namespace KodiCMS\Cron\Observers;

class JobObserver
{
	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return void
	 */
	public function saving($job)
	{
		if ($job->isDirty('interval', 'crontime', 'last_run'))
		{
			$job->setNextRun();
		}
	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return void
	 */
	public function creating($job)
	{
		$job->status = Job::STATUS_NEW;
	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return void
	 */
	public function created($job)
	{

	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return void
	 */
	public function updating($job)
	{

	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return void
	 */
	public function deleting($job)
	{

	}

	/**
	 * @param \KodiCMS\Cron\Model\Job $job
	 * @return void
	 */
	public function deleted($job)
	{

	}
}