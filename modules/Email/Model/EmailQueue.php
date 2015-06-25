<?php namespace KodiCMS\Email\Model;

use Mail;
use KodiCMS\Email\Support\EmailSender;
use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model
{

	const STATUS_PENDING = 'pending';
	const STATUS_SENT = 'sent';
	const STATUS_FAILED = 'failed';

	/**
	 * @var array
	 */
	protected $fillable = [
		'status',
		'parameters',
		'message_type',
		'body',
		'attempts',
	];

	/**
	 * @var array
	 */
	protected $casts = [
		'parameters' => 'object',
	];

	protected static function boot()
	{
		parent::boot();

		static::creating(function ($instance)
		{
			$instance->status = static::STATUS_PENDING;
			$instance->attempts = 0;
		});
	}

	/**
	 * @param EmailTemplate $emailTemplate
	 * @param array $options
	 * @return static
	 */
	public static function addEmailTemplate(EmailTemplate $emailTemplate, array $options = [])
	{
		$parameters = array_only($emailTemplate->toArray(), [
			'email_from',
			'email_to',
			'subject',
			'cc',
			'bcc',
			'reply_to',
		]);

		return static::create([
			'parameters'   => $parameters,
			'message_type' => $emailTemplate->message_type,
			'body'         => $emailTemplate->message,
		]);
	}

	public function scopePending($query)
	{
		$query->whereStatus(static::STATUS_PENDING);
	}

	public function scopeNotPending($query)
	{
		$query->where('status', '!=', static::STATUS_PENDING);
	}

	public function scopeOld($query)
	{
		$query->where('created_at', '<', \Carbon\Carbon::create()->subDays(10));
	}

	public static function sendAll()
	{
		set_time_limit(0);

		$size = config('email_queue.batch_size');
		$interval = config('email_queue.interval');

		$queue = static::pending()->get();

		$i = 0;
		foreach ($queue as $email)
		{
			if ($i >= $size)
			{
				$i = 0;
				sleep($interval);
			}
			$email->send();
			$i++;
		}
	}

	public function send()
	{
		$sended = EmailSender::send($this->body, $this->parameters, $this->message_type);

		$this->attempts++;
		if ($sended)
		{
			$this->status = static::STATUS_SENT;
		} elseif ($this->attempts >= config('email_queue.max_attempts'))
		{
			$this->status = static::STATUS_FAILED;
		}
		$this->save();
	}

	public static function cleanOld()
	{
		static::notPending()->old()->delete();
	}
} 