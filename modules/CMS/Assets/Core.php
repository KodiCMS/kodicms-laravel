<?php namespace KodiCMS\CMS\Assets;

use HTML;
use Cache;
use ModuleLoader;
use Carbon\Carbon;
use Package as PackageManager;

/**
 * Class Core
 * @package KodiCMS\CMS\Assets
 */
class Core
{
	/**
	 * @var array
	 */
	protected static $loadedPackages = [];

	/**
	 * @var  array  CSS assets
	 */
	protected $css = [];

	/**
	 * @var  array  Javascript assets
	 */
	protected $js = [];

	/**
	 * @var  array  Other asset groups (meta data, links, etc...)
	 */
	protected $groups = [];

	/**
	 * @param string|array $names
	 * @param bool $loadDependencies
	 * @param bool $footer
	 * @return bool
	 */
	public function package($names, $loadDependencies = false, $footer = false)
	{
		$names = (array) $names;

		foreach ($names as $name)
		{
			if(in_array($name, static::$loadedPackages)) continue;

			$package = PackageManager::load($name);

			if ($package === null) continue;
			static::$loadedPackages[] = $name;

			foreach ($package as $item)
			{
				if ($loadDependencies === true AND isset($item['deps']) AND is_array($item['deps']))
				{
					$this->package($item['deps'], true);
				}

				switch ($item['type'])
				{
					case 'css':
						$this->css[$item['handle']] = $item;
						break;
					case 'js':
						$item['footer'] = (bool)$footer;
						$this->js[$item['handle']] = $item;
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
	public function css($handle = null, $src = null, $deps = null, $attrs = null)
	{
		// Return all CSS assets, sorted by dependencies
		if ($handle === null) return $this->allCss();

		// Return individual asset
		if ($src === null) return $this->getCss($handle);

		// Set default media attribute
		if (!isset($attrs['media']))
		{
			$attrs['media'] = 'all';
		}

		return $this->css[$handle] = [
			'src' => $src,
			'deps' => (array)$deps,
			'attrs' => $attrs,
			'handle' => $handle,
			'type' => 'css'
		];
	}

	/**
	 * Get a single CSS asset
	 *
	 * @param   string   Asset name
	 * @return  string   Asset HTML
	 */
	public function getCss($handle)
	{
		if (!isset($this->css[$handle])) return false;

		$asset = $this->css[$handle];

		return HTML::style($asset['src'], $asset['attrs']);
	}

	/**
	 * Get all CSS assets, sorted by dependencies
	 *
	 * @return   string   Asset HTML
	 */
	public function allCss()
	{
		if (empty($this->css)) return false;

		foreach ($this->sort($this->css) as $handle => $data)
		{
			$assets[] = $this->getCss($handle);
		}

		return implode("", $assets);
	}

	/**
	 * Remove a CSS asset, or all
	 *
	 * @param   mixed   Asset name, or `NULL` to remove all
	 * @return  mixed   Empty array or void
	 */
	public function removeCss($handle = null)
	{
		if ($handle === null) return $this->css = [];

		unset($this->css[$handle]);
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
	public function js($handle = false, $src = null, $deps = null, $footer = false)
	{
		if (is_bool($handle)) return $this->allJs($handle);
		if ($src === null) return $this->getJs($handle);

		return $this->js[$handle] = [
			'src' => $src,
			'deps' => (array)$deps,
			'footer' => (bool)$footer,
			'handle' => $handle,
			'type' => 'js'
		];
	}

	/**
	 * Get a single javascript asset
	 *
	 * @param   string   Asset name
	 * @return  string   Asset HTML
	 */
	public function getJs($handle)
	{
		if (!isset($this->js[$handle])) return false;
		$asset = $this->js[$handle];

		return HTML::script($asset['src']);
	}

	/**
	 * Get all javascript assets of section (header or footer)
	 *
	 * @param   bool   FALSE for head, TRUE for footer
	 * @return  string Asset HTML
	 */
	public function allJs($footer = false)
	{
		if (empty($this->js)) return false;

		$assets = [];

		foreach ($this->js as $handle => $data)
		{
			if ($data['footer'] === $footer)
			{
				$assets[$handle] = $data;
			}
		}

		if (empty($assets)) return false;

		foreach ($this->sort($assets) as $handle => $data)
		{
			$sorted[] = $this->getJs($handle);
		}

		return implode("", $sorted);
	}

	/**
	 * Remove a javascript asset, or all
	 *
	 * @param   mixed   Remove all if `NULL`, section if `TRUE` or `FALSE`, asset if `string`
	 * @return  mixed   Empty array or void
	 */
	public function removeJs($handle = null)
	{
		if ($handle === null) return $this->js = [];

		if ($handle === true OR $handle === false)
		{
			foreach ($this->js as $handle => $data)
			{
				if ($data['footer'] === $handle)
				{
					unset($this->js[$handle]);
				}
			}

			return;
		}

		unset($this->js[$handle]);
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
	public function group($group, $handle = null, $content = null, $deps = null)
	{
		if ($handle === null) return $this->allGroup($group);
		if ($content === null) return $this->getGroup($group, $handle);

		return $this->groups[$group][$handle] = ['content' => $content, 'deps' => (array)$deps,];
	}

	/**
	 * Get a single group asset
	 *
	 * @param   string   Group name
	 * @param   string   Asset name
	 * @return  string   Asset content
	 */
	public function getGroup($group, $handle)
	{
		if (!isset($this->groups[$group]) OR !isset($this->groups[$group][$handle]))
		{
			return false;
		}

		return $this->groups[$group][$handle]['content'];
	}

	/**
	 * Get all of a groups assets, sorted by dependencies
	 *
	 * @param  string   Group name
	 * @return string   Assets content
	 */
	public function allGroup($group)
	{
		if (!isset($this->groups[$group])) return false;

		foreach ($this->sort($this->groups[$group]) as $handle => $data)
		{
			$assets[] = $this->getGroup($group, $handle);
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
	public function removeGroup($group = null, $handle = null)
	{
		if ($group === null) return $this->groups = [];

		if ($handle === null)
		{
			unset($this->groups[$group]);
			return;
		}

		unset($this->$groups[$group][$handle]);
	}

	/**
	 * Sorts assets based on dependencies
	 *
	 * @param   array   Array of assets
	 * @return  array   Sorted array of assets
	 */
	protected function sort($assets)
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
	public function mergeFiles($path, $ext)
	{
		$cacheKey = 'assets::merge::' . md5($path) . '::' . $ext;

		$content = Cache::remember($cacheKey, Carbon::now()->minute(20), function() use($path, $ext)
		{
			$return = '';
			$files = ModuleLoader::findFile('resources', $path, $ext, TRUE);

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

}