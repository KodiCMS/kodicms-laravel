<?php namespace KodiCMS\Widgets;

use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\Engine\WidgetRenderHTML;
use Cache;

abstract class WidgetAbstract implements WidgetInterface, WidgetRenderable, WidgetCacheable
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $description;

	/**
	 * @var string
	 */
	private $type;

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
	 * @param int $id
	 * @param string $type
	 * @param string $name
	 * @param string $description
	 */
	public function __construct($id, $type, $name, $description = '')
	{
		$this->id = (int) $id;
		$this->type = $type;
		$this->name = $name;
		$this->description = $description;
	}

	/**
	 * @return bool
	 */
	public function isExists()
	{
		return $this->getId() > 0;
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
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getSettingsTemplate()
	{
		return $this->settingsTemplate;
	}

	/**********************************************************************************************************
	 * Frontend Template
	 **********************************************************************************************************/

	/**
	 * @return string
	 */
	public function getFrontendTemplate()
	{
		return $this->frontendTemplate;
	}

	/**
	 * @return string
	 */
	public function getDefaultFrontendTemplate()
	{
		return $this->defaultFrontendTemplate;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) new WidgetRenderHTML($this);
	}

	/**********************************************************************************************************
	 * Cache
	 **********************************************************************************************************/
	/**
	 * @return int
	 */
	public function getCacheLifetime()
	{
		return (int) $this->getSetting('cache_lifetime', 0);
	}

	/**
	 * @return array
	 */
	public function getCacheTags()
	{
		return $this->getSetting('cache_tags', []);
	}

	/**
	 * @return string
	 */
	public function getCacheTagsAsString()
	{
		return implode(', ', $this->getCacheTags());
	}

	/**
	 * @return string
	 */
	public function getCacheKey()
	{
		return 'Widget::' . $this->getType() . '::' . $this->getId();
	}

	/**
	 * @param bool $enabled
	 * @param int $lifetime
	 * @param array $tags
	 */
	public function setCacheSettings($enabled = true, $lifetime = 300, array $tags = [])
	{
		$this->cache = $enabled;
		$this->cache_lifetime = $lifetime;
		$this->cache_tags = $tags;
	}

	/**
	 * @param bool $status
	 * @return bool
	 */
	public function setSettingCache($status)
	{
		return (bool) $status;
	}

	/**
	 * @param int $lifetime
	 * @return int
	 */
	public function setSettingCacheLifetime($lifetime)
	{
		return (int) $lifetime;
	}

	/**
	 * @param array | string $tags
	 * @return array
	 */
	public function setSettingCacheTags(array $tags)
	{
		return $tags;
	}

	/**
	 * @return boolean
	 */
	public function clearCache()
	{
		// TODO: реализовать метод очистки кеша
	}

	/**
	 * @return boolean
	 */
	public function clearCacheByTags()
	{
		// TODO: реализовать метод очистки кеша по тегам
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
				$this->parameters[$name] = $this->{$method}($value);
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
	 *
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
				$this->settings[$name] = $this->{$method}($value);
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
}