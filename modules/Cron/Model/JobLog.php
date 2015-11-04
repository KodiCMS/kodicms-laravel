<?php

namespace KodiCMS\Cron\Model;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JobLog.
 *
 * @property int $id
 * @property int $job_id
 * @property int $status
 * @property string  $status_string
 *
 * @property Job     $job
 *
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 */
class JobLog extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jobLog) {
            $jobLog->status = Job::STATUS_NEW;
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cron_job_logs';

    /**
     * @param int $value
     *
     * @throws \Exception
     */
    public function setStatus($value)
    {
        if (! $this->exists) {
            throw new Exception('Cannot set status because it is not loaded');
        }

        $this->job->status = $value;
        $this->job->save();

        $this->status = $value;
        $this->save();
    }

    /*******************************************************
     * Mutators
     *******************************************************/

    /**
     * @return string
     */
    public function getStatusStringAttribute()
    {
        return trans('cron::core.statuses.'.$this->status);
    }

    /*******************************************************
     * Relations
     *******************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
