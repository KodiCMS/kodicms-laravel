<?php namespace KodiCMS\Cron\Model;

use Illuminate\Database\Eloquent\Model;

class JobLog extends Model
{
	/**
	 * @var array
	 */
	protected $fillable = [
		'job_id',
		'status'
	];

	// TODO: вынести в отдельный Observer
	protected static function boot()
	{
		parent::boot();

		static::creating(function ($jobLog)
		{
			$jobLog->status = Job::STATUS_NEW;
		});
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function job()
	{
		return $this->belongsTo('KodiCMS\Cron\Model\Job');
	}

	public function getStatusStringAttribute()
	{
		return trans('cron::core.statuses.' . $this->status);
	}

	/**
	 * @param integer $value
	 * @throws \Exception
	 */
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