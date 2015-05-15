<?php namespace KodiCMS\Widgets\Widget;

use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Widgets\Manager\WidgetManager;

abstract class Decorator implements WidgetInterface, \ArrayAccess
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $description;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $frontendTemplate = null;

	/**
	 * @var string
	 */
	protected $defaultFrontendTemplate = null;

	/**
	 * @var array
	 */
	protected $parameters = [];

	/**
	 * @var string
	 */
	protected $settingsTemplate = null;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @param string $name
	 * @param string $description
	 */
	public function __construct($name, $description = '')
	{
		$this->type = WidgetManager::getTypeByClassName(get_called_class());
		$this->name = $name;
		$this->description = $description;
	}

	/**
	 * @return bool
	 */
	public function isExists()
	{
		return strlen($this->getId()) > 0;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @throws WidgetException
	 */
	public function setId($id)
	{
		if ($this->isExists())
		{
			// TODO: написать правильный текст
			throw new WidgetException('You can\'t change widget id');
		}

		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return array
	 */
	public function getRoles()
	{
		return (array) $this->getSetting('roles', []);
	}

	/**
	 * @param array $roles
	 */
	public function setRoles(array $roles)
	{
		$this->settings['roles'] = array_unique($roles);
	}

	/**********************************************************************************************************
	 * Parameters
	 **********************************************************************************************************/

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed|null
	 */
	public function getParameter($name, $default = null)
	{
		$method = 'getParameter' . studly_case($name);

		if (method_exists($this, $method))
		{
			return $this->{$method}($default);
		}

		return array_get($this->parameters, $name, $default);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setParameter($name, $value = null)
	{
		if (is_array($name))
		{
			$this->setParameters($name);
		}
		else
		{
			$method = 'setParameter' . studly_case($name);
			if (method_exists($this, $method))
			{
				return $this->{$method}($value);
			}
			else
			{
				$this->parameters[$name] = $value;
			}
		}

		return $this;
	}

	/**
	 * @param array $parameters
	 * @return $this
	 */
	public function setParameters(array $parameters)
	{
		foreach ($parameters as $key => $value)
		{
			$this->setParameter($key, $value);
		}

		return $this;
	}

	/**********************************************************************************************************
	 * Settings
	 **********************************************************************************************************/
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
		foreach ($settings as $key => $value)
		{
			$this->setSetting($key, $value);
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function prepareSettingsData()
	{
		return [];
	}

	/**
	 * @return string
	 */
	public function getSettingsTemplate()
	{
		return $this->settingsTemplate;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return [
			'id' => $this->getId(),
			'type' => $this->getType(),
			'name' => $this->getName(),
			'description' => $this->getDescription(),
			'settings' => $this->getSettings(),
			'parameters' => $this->getParameters()
		];
	}
}