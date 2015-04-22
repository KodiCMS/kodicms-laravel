<?php namespace KodiCMS\CMS\Helpers;

use Carbon\Carbon;
use DateTimeZone;

class Date
{
	/**
	 * @param integer|string|Carbon $date
	 * @param string $format
	 * @return string
	 */
	public static function format($date = NULL, $format = NULL)
	{
		if ($format === NULL)
		{
			$format = config('cms.date_format');
		}

		if ($date instanceof Carbon)
		{
			return $date->format($format);
		}
		else if (!is_numeric($date))
		{
			$date = strtotime($date);
		}

		if (empty($date))
		{
			return trans('cms::core.label.date_never');
		}

		return date($format, $date);
	}

	/**
	 * @return array
	 */
	public static function getTimezones()
	{
		$zones = [];

		foreach (DateTimeZone::listIdentifiers() as $zone)
		{
			$zones[$zone] = $zone;
		}

		return $zones;
	}

	/**
	 * @return array
	 */
	public static function getFormats()
	{
		$dateFormats = config('cms.date_format_list', []);
		$dateFormats = array_combine($dateFormats, $dateFormats);

		foreach($dateFormats as $format => $value)
		{
			$dateFormats[$format] = Carbon::now()->format($format);
		}

		return $dateFormats;
	}
}