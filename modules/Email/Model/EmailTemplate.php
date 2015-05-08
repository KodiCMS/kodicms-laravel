<?php namespace KodiCMS\Email\Model;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{

	const TYPE_HTML = 'html';
	const TYPE_TEXT = 'plain';

	const ACTIVE = 1;
	const INACTIVE = 0;

	const USE_QUEUE = 1;
	const USE_DIRECT = 0;

	protected $fillable = [
		'email_type_id',
		'status',
		'use_queue',
		'email_from',
		'email_to',
		'subject',
		'message',
		'message_type',
		'cc',
		'bcc',
		'reply_to',
	];

	public function type()
	{
		return $this->belongsTo('KodiCMS\Email\Model\EmailType', 'email_type_id');
	}

	protected static function boot()
	{
		parent::boot();

		static::creating(function ($emailTemplate)
		{
			if (empty($emailTemplate->message_type))
			{
				$emailTemplate->message_type = 'html';
			}
		});
	}

	public function getStatusStringAttribute()
	{
		return trans('email::core.statuses.' . $this->status);
	}

	public static function statuses()
	{
		return [
			static::ACTIVE   => trans('email::core.statuses.1'),
			static::INACTIVE => trans('email::core.statuses.0'),
		];
	}

	public static function queueStatuses()
	{
		return [
			static::USE_DIRECT => trans('email::core.queue.0'),
			static::USE_QUEUE  => trans('email::core.queue.1'),
		];
	}

}