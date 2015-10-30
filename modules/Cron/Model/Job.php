<?php
namespace KodiCMS\Cron\Model;

use Artisan;
use Carbon\Carbon;
use KodiCMS\Cron\Support\Crontab;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{

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
     * @var array
     */
    protected $fillable = [
        'name',
        'task_name',
        'date_start',
        'date_end',
        'last_run',
        'next_run',
        'interval',
        'crontime',
        'status',
        'attempts',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_start', 'date_end', 'last_run', 'next_run'];

    /**
     * @var array
     */
    protected $attributes = [
        'crontime' => '* * * * *',
    ];


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
     * @return array
     */
    public function getTypes()
    {
        return array_map(function ($item) {
            return array_get($item, 'label', '');
        }, config('jobs'));
    }


    /**
     * @param integer $value
     */
    public function setAttemptsAttribute($value)
    {
        $this->attributes['attempts'] = intval($value);
    }


    public function setNextRun()
    {
        if (empty( $this->interval ) && empty( $this->crontime )) {
            return;
        }

        if ( ! empty( $this->crontime )) {
            $this->next_run = Crontab::parse($this->crontime);
        } else {
            if ( ! empty( $this->interval )) {
                $this->next_run = with(new Carbon())->addMinutes($this->interval);
            }
        }
    }


    /**
     * @return string
     */
    public function getStatusStringAttribute()
    {
        return trans('cron::core.statuses.' . $this->status);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(JobLog::class);
    }


    public static function runAll()
    {
        $now  = new Carbon;
        $jobs = static::where('attempts', '<=', static::MAX_ATEMTPS)
            ->where('date_start', '<=', $now)->where('date_end', '>=', $now)
            ->where('next_run', '<', $now)->get();

        foreach ($jobs as $job) {
            $job->run();
        }
    }


    public function run()
    {
        $log = new JobLog;
        $log->job()->associate($this);
        $log->save();

        $log->setStatus(static::STATUS_RUNNING);

        try {
            $action = config('jobs.' . $this->task_name . '.action');
            if (is_null($action)) {
                throw new \Exception('Job not found or action not set');
            }

            if (strpos($action, '@') !== false) {
                list( $class, $method ) = explode('@', $action);
                $instance = app($class);
                if ( ! method_exists($instance, $method)) {
                    throw new \Exception('Invalid method ' . $method);
                }
                $instance->$method();
            } else {
                Artisan::call($action);
            }
        } catch (\Exception $e) {
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
        $this->update([
            'last_run' => new Carbon,
            'attempts' => 0,
        ]);
    }


    /**
     * @param JobLog $log
     *
     * @throws \Exception
     */
    public function failed(JobLog $log)
    {
        $log->setStatus(static::STATUS_FAILED);
        $this->update([
            'last_run' => new Carbon,
            'attempts' => $this->attempts + 1,
        ]);
    }
}