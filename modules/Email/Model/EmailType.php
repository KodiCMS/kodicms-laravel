<?php namespace KodiCMS\Email\Model;

use Illuminate\Database\Eloquent\Model;

class EmailType extends Model
{

	protected $fillable = [
		'code',
		'name',
		'fields',
	];

	protected $casts = [
		'fields' => 'array',
	];

	public function templates()
	{
		return $this->hasMany('KodiCMS\Email\Model\EmailTemplate', 'email_type_id');
	}

	public function getFullNameAttribute()
	{
		return $this->name . ' (' . $this->code . ')';
	}

	public static function get($code)
	{
		return static::whereCode($code)->first();
	}

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

	public function send($options = [])
	{
		$options = array_merge($options, $this->defaultOptions());
		$templates = $this->templates()->active()->get();
		foreach ($templates as $template)
		{
			$template->send($options);
		}
	}

}