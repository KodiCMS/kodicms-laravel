<?php namespace KodiCMS\Installer;


class EnvironmentTester
{

	const KEY_HAS_ERROR = 0;
	const KEY_REQUIRED_TEST = 1;
	const KEY_OPTIONAL_TEST = 2;

	/**
	 * @return array
	 */
	public static function check()
	{
		// Clearing the realpath() cache is only possible PHP 5.3+
		clearstatcache(true);

		$env = new static;

		$result = [
			static::KEY_HAS_ERROR => false,
			static::KEY_REQUIRED_TEST => [],
			static::KEY_OPTIONAL_TEST => []
		];

		$isFailed = false;

		foreach ($methods as $method)
		{
			if (strpos($method, 'test') === false)
			{
				continue;
			}

			$return = call_user_func([$env, $method]);
			if (empty($return))
			{
				continue;
			}

			$testType = static::KEY_REQUIRED_TEST;
			if (strpos($method, 'optional') !== false)
			{
				$testType = static::KEY_OPTIONAL_TEST;
			}

			$status = $return['condition'];

			$data = [
				'title' => $return['title'],
				'failed' => !$status,
				'notice' => array_get($return, 'notice')
			];

			if ($status)
			{
				$data['message'] = array_get($return, 'success', trans('installer::core.tests.pass'));
			}
			else
			{
				$data['message'] = array_get($return, 'error', trans('installer::core.tests.failed'));

				if ($testType === static::KEY_REQUIRED_TEST)
				{
					$isFailed = true;
				}
			}

			$result[$testType][substr($method, 4)] = $data;
		}

		$result[static::KEY_HAS_ERROR] = $isFailed;

		return $result;
	}

	public function testPHP()
	{
		return [
			'title' => 'PHP Version',
			'condition' => version_compare(PHP_VERSION, '5.4', '>='),
			'error' => trans('installer::core.tests.error.php_version', ['version' => PHP_VERSION]),
			'success' => PHP_VERSION
		];
	}
}