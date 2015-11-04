<?php

namespace KodiCMS\Cron\Model;

use Artisan;
use Exception;
use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Job.
 *
 * @property int $id
 * @property string  $name
 * @property string  $task_name
 * @property string  $crontime
 * @property int $interval
 * @property int $status
 * @property int $attempts
 *
 * @property Carbon $date_start
 * @property Carbon $date_end
 * @property Carbon $last_run
 * @property Carbon $next_run
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property JobLog[] $logs
 */
class Job extends Model
{
    /**
     * @return Collection
     */
    public static function runAll()
    {
        return static::onlyActive()
                     ->where('next_run', '<', new Carbon)
                     ->get()
                     ->each(function (Job $job) {
                         $job->run();
                     });
    }

    /**
     * @return array
     */
    public static function agents()
    {
        return [
            static::AGENT_SYSTEM => trans('cron::core.settings.agents.system'),
            static::AGENT_CRON   => trans('cron::core.settings.agents.cron'),
        ];
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cron_jobs';

    const STATUS_FAILED = -1;    // Job has failed
    const STATUS_NEW = 1;        // New job
    const STATUS_RUNNING = 2;    // Job is currently running
    const STATUS_COMPLETED = 3;  // Job is complete

    const AGENT_SYSTEM = 0;
    const AGENT_CRON = 1;

    const MAX_ATEMTPS = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'task_name',
        'date_start',
        'date_end',
        'interval',
        'crontime',
    ];

    /**
     * The model's dates.
     *
     * @var array
     */
    protected $dates = ['date_start', 'date_end', 'last_run', 'next_run'];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'crontime' => '* * * * *',
    ];

    /**
     * @return array
     */
    public function getTypes()
    {
        return array_map(function ($item) {
            return array_get($item, 'label', '');
        }, config('jobs'));
    }

    public function setNextRun()
    {
        if (empty($this->interval) && empty($this->crontime)) {
            return;
        }

        if (! empty($this->crontime)) {
            $this->next_run = Carbon::create(
                CronExpression::factory($this->crontime)->getNextRunDate()->format('Y-m-d H:i:s')
            );
        } elseif (! empty($this->interval)) {
            $this->next_run = (new Carbon())->addMinutes($this->interval);
        }
    }

    public function run()
    {
        $log = $this->logs()->create();
        $log->setStatus(static::STATUS_RUNNING);

        try {
            $action = config('jobs.'.$this->task_name.'.action');
            if (is_null($action)) {
                throw new Exception('Job not found or action not set');
            }

            if (strpos($action, '@') !== false) {
                list($class, $method) = explode('@', $action);
                $instance = app($class);
                if (! method_exists($instance, $method)) {
                    throw new Exception('Invalid method '.$method);
                }
                $instance->$method();
            } else {
                Artisan::call($action);
            }
        } catch (Exception $e) {
            $this->failed($log);

            return;
        }

        $this->completed($log);
    }

    /**
     * @param JobLog $log
     */
    public function completed(JobLog $log)
    {
        $log->setStatus(static::STATUS_COMPLETED);

        $this->last_run = new Carbon;
        $this->attempts = 0;

        $this->save();
    }

    /**
     * @param JobLog $log
     *
     * @throws Exception
     */
    public function failed(JobLog $log)
    {
        $log->setStatus(static::STATUS_FAILED);

        $this->last_run = new Carbon;
        $this->attempts += 1;

        $this->save();
    }

    /*******************************************************
     * Mutators
     *******************************************************/

    /**
     * @param int $value
     */
    public function setAttemptsAttribute($value)
    {
        $this->attributes['attempts'] = intval($value);
    }

    /**
     * @return string
     */
    public function getStatusStringAttribute()
    {
        return trans('cron::core.statuses.'.$this->status);
    }

    /*******************************************************
     * Scopes
     *******************************************************/

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOnlyActive(Builder $query)
    {
        $now = new Carbon;

        return $query->where('attempts', '<=', static::MAX_ATEMTPS)
                     ->where('date_start', '<=', $now)
                     ->where('date_end', '>=', $now);
    }

    /*******************************************************
     * Relations
     *******************************************************/

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(JobLog::class);
    }
}
