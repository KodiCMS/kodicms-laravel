<?php namespace KodiCMS\CMS\Assets;

use HTML;
use Cache;
use Carbon\Carbon;

class Core
{
	/**
	 * @var  array  CSS assets
	 */
	protected static $css = [];

	/**
	 * @var  array  Javascript assets
	 */
	protected static $js = [];

	/**
	 * @var  array  Other asset groups (meta data, links, etc...)
	 */
	protected static $groups = [];

	/**
	 * @param string|array $names
	 * @param bool $loadDependencies
	 * @param bool $footer
	 * @return bool
	 */
	public static function package($names, $loadDependencies = false, $footer = false)
	{
		if (!is_array($names))
		{
			$names = [$names];
		}

		foreach ($names as $name)
		{
			$package = Package::load($name);

			if ($package === null) continue;

			foreach ($package as $item)
			{
				if($loadDependencies === true AND isset($item['deps']) AND is_array($item['deps']))
				{
					static::package($item['deps'], true);
				}

				switch ($item['type'])
				{
					case 'css':
						static::$css[$item['handle']] = $item;
						break;
					case 'js':
						$item['footer'] = (bool)$footer;
						static::$js[$item['handle']] = $item;
						break;
				}
			}
		}

		return true;
	}

	/**
	 * CSS wrapper
	 *
	 * Gets or sets CSS assets
	 *
	 * @param   string   Asset name.
	 * @param   string   Asset source
	 * @param   mixed    Dependencies
	 * @param   array    Attributes for the <link /> element
	 * @return  mixed    Setting returns asset array, getting returns asset HTML
	 */
	public static function css($handle = null, $src = null, $deps = null, $attrs = null)
	{
		// Return all CSS assets, sorted by dependencies
		if ($handle === null)
		{
			return static::allCss();
		}

		// Return individual asset
		if ($src === null)
		{
			return static::getCss($handle);
		}

		// Set default media attribute
		if (!isset($attrs['media']))
		{
			$attrs['media'] = 'all';
		}

		return static::$css[$handle] = ['src' => $src, 'deps' => (array)$deps, 'attrs' => $attrs, 'handle' => $handle, 'type' => 'css'];
	}

	/**
	 * Get a single CSS asset
	 *
	 * @param   string   Asset name
	 * @return  string   Asset HTML
	 */
	public static function getCss($handle)
	{
		if (!isset(static::$css[$handle]))
		{
			return false;
		}

		$asset = static::$css[$handle];

		return HTML::style($asset['src'], $asset['attrs']);
	}

	/**
	 * Get all CSS assets, sorted by dependencies
	 *
	 * @return   string   Asset HTML
	 */
	public static function allCss()
	{
		if (empty(static::$css))
		{
			return false;
		}

		foreach (static::sort(static::$css) as $handle => $data)
		{
			$assets[] = static::getCss($handle);
		}

		return implode("", $assets);
	}

	/**
	 * Remove a CSS asset, or all
	 *
	 * @param   mixed   Asset name, or `NULL` to remove all
	 * @return  mixed   Empty array or void
	 */
	public static function removeCss($handle = null)
	{
		if ($handle === null)
		{
			return static::$css = [];
		}

		unset(static::$css[$handle]);
	}

	/**
	 * Javascript wrapper
	 *
	 * Gets or sets javascript assets
	 *
	 * @param   bool|string    $handle
	 * @param   string   Asset source
	 * @param   mixed    Dependencies
	 * @param   bool     Whether to show in header or footer
	 * @return  mixed    Setting returns asset array, getting returns asset HTML
	 */
	public static function js($handle = false, $src = null, $deps = null, $footer = false)
	{
		if (is_bool($handle))
		{
			return static::allJs($handle);
		}

		if ($src === null)
		{
			return static::getJs($handle);
		}

		return static::$js[$handle] = ['src' => $src, 'deps' => (array)$deps, 'footer' => (bool)$footer, 'handle' => $handle, 'type' => 'js'];
	}

	/**
	 * Get a single javascript asset
	 *
	 * @param   string   Asset name
	 * @return  string   Asset HTML
	 */
	public static function getJs($handle)
	{
		if (!isset(static::$js[$handle]))
		{
			return false;
		}

		$asset = static::$js[$handle];

		return HTML::script($asset['src']);
	}

	/**
	 * Get all javascript assets of section (header or footer)
	 *
	 * @param   bool   FALSE for head, TRUE for footer
	 * @return  string Asset HTML
	 */
	public static function allJs($footer = false)
	{
		if (empty(static::$js))
		{
			return false;
		}

		$assets = [];

		foreach (static::$js as $handle => $data)
		{
			if ($data['footer'] === $footer)
			{
				$assets[$handle] = $data;
			}
		}

		if (empty($assets))
		{
			return false;
		}

		foreach (static::sort($assets) as $handle => $data)
		{
			$sorted[] = static::getJs($handle);
		}

		return implode("", $sorted);
	}

	/**
	 * Remove a javascript asset, or all
	 *
	 * @param   mixed   Remove all if `NULL`, section if `TRUE` or `FALSE`, asset if `string`
	 * @return  mixed   Empty array or void
	 */
	public static function removeJs($handle = null)
	{
		if ($handle === null)
		{
			return static::$js = [];
		}

		if ($handle === true OR $handle === false)
		{
			foreach (static::$js as $handle => $data)
			{
				if ($data['footer'] === $handle)
				{
					unset(static::$js[$handle]);
				}
			}

			return;
		}

		unset(static::$js[$handle]);
	}

	/**
	 * Group wrapper
	 *
	 * @param   string   Group name
	 * @param   string   Asset name
	 * @param   string   Asset content
	 * @param   mixed    Dependencies
	 * @return  mixed    Setting returns asset array, getting returns asset content
	 */
	public static function group($group, $handle = null, $content = null, $deps = null)
	{
		if ($handle === null)
		{
			return static::allGroup($group);
		}

		if ($content === null)
		{
			return static::getGroup($group, $handle);
		}

		return static::$groups[$group][$handle] = ['content' => $content, 'deps' => (array)$deps,];
	}

	/**
	 * Get a single group asset
	 *
	 * @param   string   Group name
	 * @param   string   Asset name
	 * @return  string   Asset content
	 */
	public static function getGroup($group, $handle)
	{
		if (!isset(static::$groups[$group]) OR !isset(static::$groups[$group][$handle]))
		{
			return false;
		}

		return static::$groups[$group][$handle]['content'];
	}

	/**
	 * Get all of a groups assets, sorted by dependencies
	 *
	 * @param  string   Group name
	 * @return string   Assets content
	 */
	public static function allGroup($group)
	{
		if (!isset(static::$groups[$group]))
		{
			return false;
		}

		foreach (static::sort(static::$groups[$group]) as $handle => $data)
		{
			$assets[] = static::getGroup($group, $handle);
		}

		return implode("", $assets);
	}

	/**
	 * Remove a group asset, all of a groups assets, or all group assets
	 *
	 * @param   string   Group name
	 * @param   string   Asset name
	 * @return  mixed    Empty array or void
	 */
	public static function removeGroup($group = null, $handle = null)
	{
		if ($group === null)
		{
			return static::$groups = [];
		}

		if ($handle === null)
		{
			unset(static::$groups[$group]);

			return;
		}

		unset(static::$groups[$group][$handle]);
	}

	/**
	 * Sorts assets based on dependencies
	 *
	 * @param   array   Array of assets
	 * @return  array   Sorted array of assets
	 */
	protected static function sort($assets)
	{
		$original = $assets;
		$sorted = [];

		while (count($assets) > 0)
		{
			foreach ($assets as $key => $value)
			{
				// No dependencies anymore, add it to sorted
				if (empty($assets[$key]['deps']))
				{
					$sorted[$key] = $value;
					unset($assets[$key]);
				}
				else
				{
					foreach ($assets[$key]['deps'] as $k => $v)
					{
						// Remove dependency if doesn't exist, if its dependent on itself, or if the dependent is dependent on it
						if (!isset($original[$v]) OR $v === $key OR (isset($assets[$v]) AND in_array($key, $assets[$v]['deps'])))
						{
							unset($assets[$key]['deps'][$k]);
							continue;
						}

						// This dependency hasn't been sorted yet
						if (!isset($sorted[$v])) continue;

						// This dependency is taken care of, remove from list
						unset($assets[$key]['deps'][$k]);
					}
				}
			}
		}

		return $sorted;
	}

	/**
	 * @param string $path
	 * @param string $ext
	 * @return string
	 */
	public static function mergeFiles($path, $ext)
	{
		$cacheKey = 'assets::merge::' . md5($path) . '::' . $ext;

		$content = Cache::remember($cacheKey, Carbon::now()->minute(20), function() use($path, $ext)
		{
			$return = '';
			$files = app('module.loader')->findFile('resources', $path, $ext, TRUE);

			foreach($files as $file)
			{
				if(config('app.debug'))
				{
					$return .= "{$file}\n";
				}

				$return .= file_get_contents($file) . "\n";
			}

			return $return;
		});

		return $content;
	}

	/**
	 * Enforce static usage
	 */
	private function __contruct(){}

	private function __clone(){}
}