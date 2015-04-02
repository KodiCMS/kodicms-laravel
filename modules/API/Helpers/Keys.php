<?php namespace KodiCMS\API\Helpers;

class Keys {

	/**
	 * @return string
	 */
	public static function generate()
	{
		$microTime = microtime();
		list($a_dec, $a_sec) = explode(' ', $microTime);

		$dec_hex = dechex($a_dec * 1000000);
		$sec_hex = dechex($a_sec);

		$dec_hex = static::_ensure_length($dec_hex, 5);
		$sec_hex = static::_ensure_length($sec_hex, 6);

		$guid = '';
		$guid .= $dec_hex;
		$guid .= static::_create_guid_section(3);
		$guid .= '-';
		$guid .= static::_create_guid_section(4);
		$guid .= '-';
		$guid .= static::_create_guid_section(4);
		$guid .= '-';
		$guid .= static::_create_guid_section(4);
		$guid .= '-';
		$guid .= $sec_hex;
		$guid .= static::_create_guid_section(6);

		return $guid;
	}

	/**
	 * @param string $characters
	 * @return string
	 */
	private static function _create_guid_section($characters)
	{
		$characters = (int) $characters;
		$return = '';

		for ($i = 0; $i < $characters; $i++)
		{
			$return .= dechex(mt_rand(0, 15));
		}

		return $return;
	}

	/**
	 * @param string $string
	 * @param string $length
	 * @return string
	 */
	private static function _ensure_length($string, $length)
	{
		$length = (int) $length;
		$strlen = strlen($string);

		if ($strlen < $length)
		{
			$string = str_pad($string, $length, 0);
		}
		else if ($strlen > $length)
		{
			$string = substr($string, 0, $length);
		}

		return $string;
	}
}