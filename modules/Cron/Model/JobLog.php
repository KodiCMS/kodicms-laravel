<?php namespace KodiCMS\Cron\Model;

use Illuminate\Database\Eloquent\Model;

class JobLog extends Model
{

	protected $fillable = [
		'job_id',
		'status'
	];

	protected static function boot()
	{
		parent::boot();

		static::creating(function ($jobLog)
		{
			$jobLog->status = Job::STATUS_NEW;
		});
	}

	public function job()
	{
		return $this->belongsTo('KodiCMS\Cron\Model\Job');
	}

	public function getStatusStringAttribute()
	{
		return trans('cron::core.statuses.' . $this->status);
	}

	public function setStatus($value)
	{
		if ( ! $this->exists)
		{
			throw new \Exception('Cannot set status because it is not loaded');
		}

		$this->job->status = $value;
		$this->job->save();

		$this->status = $value;
		$this->save();
	}

}