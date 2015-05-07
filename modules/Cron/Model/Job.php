<?php namespace KodiCMS\Cron\Model;

use Artisan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use KodiCMS\Cron\Support\Crontab;

class Job extends Model
{

	const STATUS_FAILED = -1;    // Job has failed
	const STATUS_NEW = 1;        // New job
	const STATUS_RUNNING = 2;    // Job is currently running
	const STATUS_COMPLETED = 3;   // Job is complete

	const AGENT_SYSTEM = 0;
	const AGENT_CRON = 1;

	const MAX_ATEMTPS = 5;

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

	protected static function boot()
	{
		parent::boot();

		static::creating(function ($job)
		{
			$job->status = static::STATUS_NEW;
		});
		static::saving(function ($job)
		{
			if ($job->isDirty('interval', 'crontime', 'last_run'))
			{
				$job->setNextRun();
			}
		});
	}

	public static function agents()
	{
		return [
			static::AGENT_SYSTEM => trans('cron::core.settings.agents.system'),
			static::AGENT_CRON   => trans('cron::core.settings.agents.cron')
		];
	}

	public function getDates()
	{
		$dates = [
			'date_start',
			'date_end',
			'last_run',
			'next_run',
		];
		return array_merge(parent::getDates(), $dates);
	}

	public function getTypes()
	{
		return array_map(function ($item)
		{
			return array_get($item, 'label', '');
		}, config('jobs'));
	}

	public function setAttemptsAttribute($value)
	{
		$this->attributes['attempts'] = intval($value);
	}

	public function setNextRun()
	{
		if (empty($this->interval) && empty($this->crontime))
		{
			return;
		}
		if ( ! empty($this->crontime))
		{
			$this->next_run = Crontab::parse($this->crontime);
		} else
		{
			if ( ! empty($this->interval))
			{
				$this->next_run = with(new Carbon())->addSeconds($this->interval);
			}
		}
	}

	public function getStatusStringAttribute()
	{
		return trans('cron::core.statuses.' . $this->status);
	}

	public function logs()
	{
		return $this->hasMany('KodiCMS\Cron\Model\JobLog');
	}

	public static function runAll()
	{
		$now = new Carbon;
		$jobs = static::where('attempts', '<=', static::MAX_ATEMTPS)->where('date_start', '<=', $now)->where('date_end', '>=', $now)->where('next_run', '<', $now)->get();

		foreach ($jobs as $job)
		{
			$job->run();
		}
	}

	public function run()
	{
		$log = new JobLog;
		$log->job()->associate($this);
		$log->save();

		$log->setStatus(static::STATUS_RUNNING);

		try
		{
			$action = config('jobs.' . $this->task_name . '.action');
			if (is_null($action))
			{
				throw new \Exception('Job not found or action not set');
			}

			if (strpos($action, '@') !== false)
			{
				list($class, $method) = explode('@', $action);
				$instance = app($class);
				if ( ! method_exists($instance, $method))
				{
					throw new \Exception('Invalid method ' . $method);
				}
				$instance->$method();
			} else
			{
				Artisan::call($action);
			}
		} catch (\Exception $e)
		{
			dd($e);
			$this->failed($log);
			return;
		}
		$this->completed($log);
	}

	public function completed($log)
	{
		$log->setStatus(static::STATUS_COMPLETED);
		$this->update([
			'last_run' => new Carbon,
			'attempts' => 0,
		]);
	}

	public function failed($log)
	{
		$log->setStatus(static::STATUS_FAILED);
		$this->update([
			'last_run' => new Carbon,
			'attempts' => $this->attempts + 1,
		]);
	}

}