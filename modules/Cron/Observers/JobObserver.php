<?php

namespace KodiCMS\Cron\Observers;

use KodiCMS\Cron\Model\Job;

class JobObserver
{
    /**
     * @param Job $job
     */
    public function saving(Job $job)
    {
        if ($job->isDirty('interval', 'crontime', 'last_run')) {
            $job->setNextRun();
        }
    }

    /**
     * @param Job $job
     */
    public function creating(Job $job)
    {
        $job->status = Job::STATUS_NEW;
    }
}
