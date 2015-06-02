<?php namespace KodiCMS\Email\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Email\Support\EmailSender;
use Mail;

class EmailTemplate extends Model
{

	const TYPE_HTML = 'html';
	const TYPE_TEXT = 'plain';

	const ACTIVE = 1;
	const INACTIVE = 0;

	const USE_QUEUE = 1;
	const USE_DIRECT = 0;

	/**
	 * @var array
	 */
	protected $fillable = [
		'email_event_id',
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

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function event()
	{
		return $this->belongsTo('KodiCMS\Email\Model\EmailEvent', 'email_event_id');
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

	public function scopeActive($query)
	{
		$query->whereStatus(static::ACTIVE);
	}

	/**
	 * @return string
	 */
	public function getStatusStringAttribute()
	{
		return trans('email::core.statuses.' . $this->status);
	}

	/**
	 * @return array
	 */
	public static function statuses()
	{
		return [
			static::ACTIVE   => trans('email::core.statuses.1'),
			static::INACTIVE => trans('email::core.statuses.0'),
		];
	}

	/**
	 * @return array
	 */
	public static function queueStatuses()
	{
		return [
			static::USE_DIRECT => trans('email::core.queue.0'),
			static::USE_QUEUE  => trans('email::core.queue.1'),
		];
	}

	public function send($options = [])
	{
		$options = $this->prepareOptions($options);
		$this->prepareInnerValues($options);

		if ($this->use_queue)
		{
			return $this->addToQueue($options);
		}
		else
		{
			return EmailSender::send($this->message, $this, $this->message_type);
		}
	}

	/**
	 * @param array $options
	 * @return static
	 */
	public function addToQueue(array $options = [])
	{
		return EmailQueue::addEmailTemplate($this, $options);
	}

	/**
	 * @param array $options
	 * @return array
	 */
	protected function prepareOptions(array $options = [])
	{
		$prepared = [];
		foreach ($options as $key => $value)
		{
			$prepared['{' . $key . '}'] = $value;
		}

		return $prepared;
	}

	/**
	 * @param array $options
	 */
	protected function prepareInnerValues(array $options = [])
	{
		foreach ($this->fillable as $field)
		{
			$this->$field = strtr($this->$field, $options);
		}
	}
}