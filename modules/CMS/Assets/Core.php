<?php namespace KodiCMS\CMS\Assets;

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
	 * @param boolean $footer
	 * @return boolean
	 */
	public static function package($names, $footer = FALSE)
	{
		if (!is_array($names)) {
			$names = [$names];
		}

		foreach ($names as $name) {
			$package = Package::load($name);

			if ($package === NULL)
				continue;

			foreach ($package as $item) {
				switch ($item['type']) {
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

		return TRUE;
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
	public static function css($handle = NULL, $src = NULL, $deps = NULL, $attrs = NULL)
	{
		// Return all CSS assets, sorted by dependencies
		if ($handle === NULL) {
			return static::allCss();
		}

		// Return individual asset
		if ($src === NULL) {
			return static::getCss($handle);
		}

		// Set default media attribute
		if (!isset($attrs['media'])) {
			$attrs['media'] = 'all';
		}

		return static::$css[$handle] = [
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
	public static function getCss($handle)
	{
		if (!isset(static::$css[$handle])) {
			return FALSE;
		}

		$asset = static::$css[$handle];
		return \HTML::style($asset['src'], $asset['attrs']);
	}

	/**
	 * Get all CSS assets, sorted by dependencies
	 *
	 * @return   string   Asset HTML
	 */
	public static function allCss()
	{
		if (empty(static::$css)) {
			return FALSE;
		}

		foreach (static::sort(static::$css) as $handle => $data) {
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
	public static function removeCss($handle = NULL)
	{
		if ($handle === NULL) {
			return static::$css = [];
		}

		unset(static::$css[$handle]);
	}

	/**
	 * Javascript wrapper
	 *
	 * Gets or sets javascript assets
	 *
	 * @param   mixed    Asset name if `string`, sets `$footer` if boolean
	 * @param   string   Asset source
	 * @param   mixed    Dependencies
	 * @param   bool     Whether to show in header or footer
	 * @return  mixed    Setting returns asset array, getting returns asset HTML
	 */
	public static function js($handle = FALSE, $src = NULL, $deps = NULL, $footer = FALSE)
	{
		if ($handle === TRUE OR $handle === FALSE) {
			return static::allJs($handle);
		}

		if ($src === NULL) {
			return static::getJs($handle);
		}

		return static::$js[$handle] = [
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
	public static function getJs($handle)
	{
		if (!isset(static::$js[$handle])) {
			return FALSE;
		}

		$asset = static::$js[$handle];
		return \HTML::script($asset['src']);
	}

	/**
	 * Get all javascript assets of section (header or footer)
	 *
	 * @param   bool   FALSE for head, TRUE for footer
	 * @return  string Asset HTML
	 */
	public static function allJs($footer = FALSE)
	{
		if (empty(static::$js)) {
			return FALSE;
		}

		$assets = [];

		foreach (static::$js as $handle => $data) {
			if ($data['footer'] === $footer) {
				$assets[$handle] = $data;
			}
		}

		if (empty($assets)) {
			return FALSE;
		}

		foreach (static::sort($assets) as $handle => $data) {
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
	public static function removeJs($handle = NULL)
	{
		if ($handle === NULL) {
			return static::$js = [];
		}

		if ($handle === TRUE OR $handle === FALSE) {
			foreach (static::$js as $handle => $data) {
				if ($data['footer'] === $handle) {
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
	public static function group($group, $handle = NULL, $content = NULL, $deps = NULL)
	{
		if ($handle === NULL) {
			return static::allGroup($group);
		}

		if ($content === NULL) {
			return static::getGroup($group, $handle);
		}

		return static::$groups[$group][$handle] = [
			'content' => $content,
			'deps' => (array)$deps,
		];
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
		if (!isset(static::$groups[$group]) OR !isset(static::$groups[$group][$handle])) {
			return FALSE;
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
		if (!isset(static::$groups[$group])) {
			return FALSE;
		}

		foreach (static::sort(static::$groups[$group]) as $handle => $data) {
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
	public static function removeGroup($group = NULL, $handle = NULL)
	{
		if ($group === NULL) {
			return static::$groups = [];
		}

		if ($handle === NULL) {
			unset(static::$groups[$group]);
			return;
		}

		unset(static::$groups[$group][$handle]);
	}

	/**
	 * @param string $path
	 * @param string $ext
	 * @param string $cache_key
	 * @param integer $lifetime
	 * @return string
	 */
	public static function mergeFiles($path, $ext, $cacheKey = NULL, $lifetime = Date::DAY)
	{
		$cache = Cache::instance();

		if ($cache_key === NULL)
		{
			$cache_key = 'assets::merge::' . URL::title($path, '::') . '::' . $ext;
		}

		$content = $cache->get($cache_key);

		if ($content === NULL)
		{
			$files = Kohana::find_file('media', FileSystem::normalize_path($path), $ext, TRUE);
			if (!empty($files))
			{
				foreach ($files as $file)
				{
					$content .= file_get_contents($file) . "\n";
				}

				if (Kohana::$caching === TRUE)
				{
					$cache->set($cache_key, $content, $lifetime);
				}
			}
		}

		return $content;
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

		while (count($assets) > 0) {
			foreach ($assets as $key => $value) {
				// No dependencies anymore, add it to sorted
				if (empty($assets[$key]['deps'])) {
					$sorted[$key] = $value;
					unset($assets[$key]);
				} else {
					foreach ($assets[$key]['deps'] as $k => $v) {
						// Remove dependency if doesn't exist, if its dependent on itself, or if the dependent is dependent on it
						if (!isset($original[$v]) OR $v === $key OR (isset($assets[$v]) AND in_array($key, $assets[$v]['deps']))) {
							unset($assets[$key]['deps'][$k]);
							continue;
						}

						// This dependency hasn't been sorted yet
						if (!isset($sorted[$v]))
							continue;

						// This dependency is taken care of, remove from list
						unset($assets[$key]['deps'][$k]);
					}
				}
			}
		}

		return $sorted;
	}

	/**
	 * Enforce static usage
	 */
	private function __contruct(){}
	private function __clone(){}
}