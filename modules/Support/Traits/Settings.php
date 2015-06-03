<?php namespace KodiCMS\Support\Traits;

trait Settings {

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->getSetting($name);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->setSetting($name, $value);
	}

	/**
	 * @param string $name
	 * @return boolean
	 */
	public function __isset($name)
	{
		return isset($this->settings[$name]);
	}

	/**
	 * @param $name
	 */
	public function __unset($name)
	{
		unset($this->settings[$name]);
	}

	/**
	 * @param string $offset
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->setSetting($offset, $value);
	}

	/**
	 * @param string $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->settings[$offset]);
	}

	/**
	 * @param $offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->settings[$offset]);
	}

	/**
	 * @param string $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->getSetting($offset);
	}

	/**
	 * @return array
	 */
	public function getSettings()
	{
		return $this->settings;
	}

	/**
	 * @return array
	 */
	public function booleanSettings()
	{
		return [];
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed|null
	 */
	public function getSetting($name, $default = null)
	{
		$method = 'getSetting' . studly_case($name);

		if (method_exists($this, $method))
		{
			return $this->{$method}($default);
		}

		return array_get($this->settings, $name, $default);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setSetting($name, $value = null)
	{
		if (is_array($name))
		{
			$this->setSettings($name);
		}
		else
		{
			$method = 'setSetting' . studly_case($name);
			if (method_exists($this, $method))
			{
				return $this->{$method}($value);
			}
			else
			{
				$this->settings[$name] = $value;
			}
		}

		return $this;
	}

	/**
	 * @param array $settings
	 * @return $this
	 */
	public function setSettings(array $settings)
	{
		$booleans = $this->booleanSettings();
		foreach ($booleans as $key)
		{
			$settings[$key] = !empty($settings[$key]) ? true : false;
		}

		foreach ($settings as $key => $value)
		{
			$this->setSetting($key, $value);
		}

		return $this;
	}

	/**
	 * @param array $settings
	 * @return $this
	 */
	public function replaceSettings(array $settings)
	{
		$this->settings = [];
		return $this->setSettings($settings);
	}
}