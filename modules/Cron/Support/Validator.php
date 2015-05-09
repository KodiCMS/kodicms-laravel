<?php namespace KodiCMS\Cron\Support;

class Validator
{
	/**
	 * @param $attribute
	 * @param string $value
	 * @return bool
	 */
	public function validateCrontab($attribute, $value)
	{
		return Crontab::valid($value);
	}
	
} 