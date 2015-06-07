<?php namespace KodiCMS\Email\Model;

use Illuminate\Database\Eloquent\Model;

class EmailEvent extends Model
{
	/**
	 * @var array
	 */
	protected $fillable = [
		'code',
		'name',
		'fields',
	];

	/**
	 * @var array
	 */
	protected $casts = [
		'fields' => 'array',
	];

	/**
	 * @return string
	 */
	public function getNotFoundMessage()
	{
		return trans('email::core.messages.events.not_found');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function templates()
	{
		return $this->hasMany('KodiCMS\Email\Model\EmailTemplate', 'email_event_id');
	}

	/**
	 * @return string
	 */
	public function getFullNameAttribute()
	{
		return $this->name . ' (' . $this->code . ')';
	}

	/**
	 * @param string $code
	 * @return EmailEvent
	 */
	public static function get($code)
	{
		return static::whereCode($code)->first();
	}

	/**
	 * @return array
	 */
	public function defaultOptions()
	{
		$now = \Carbon\Carbon::create();
		return [
			'default_email'    => config('mail.default'),
			'site_title'       => config('cms.title'),
			'site_description' => config('cms.description'),
			'base_url'         => url('/'),
			'current_time'     => $now->format('H:i:s'),
			'current_date'     => $now->format(config('cms.date_format')),
		];
	}

	/**
	 * @param array $options
	 */
	public function send(array $options = [])
	{
		$options = array_merge($options, $this->defaultOptions());
		$templates = $this->templates()->active()->get();
		foreach ($templates as $template)
		{
			$template->send($options);
		}
	}

}