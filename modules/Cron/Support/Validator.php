<?php namespace KodiCMS\Cron\Support;

class Validator
{

	public function validateCrontab($attribute, $value)
	{
		return Crontab::valid($value);
	}
	
} 