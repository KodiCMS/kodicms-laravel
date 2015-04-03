<?php namespace KodiCMS\CMS\Assets;

class Package implements \Iterator
{
	/**
	 * @var array
	 */
	protected static $list = [];

	/**
	 * Добавление пакета
	 *
	 * @param string $name
	 * @return Package
	 */
	public static function add($name)
	{
		return new static($name);
	}

	/**
	 * Загрузка пакета
	 *
	 * @param string $name
	 * @return Package|NULL
	 */
	public static function load($name)
	{
		return array_get(static::$list, $name);
	}

	/**
	 * Получение списка всех пакетов
	 * @return array
	 */
	public static function getAll()
	{
		return static::$list;
	}

	/**
	 * @param array|string $names
	 * @return array
	 */
	public static function getScripts($names)
	{
		if (!is_array($names)) {
			$names = [$names];
		}

		$scripts = [];

		foreach ($names as $name) {
			$package = static::load($name);

			if ($package === NULL) {
				continue;
			}

			foreach ($package as $item) {
				switch ($item['type']) {
					case 'js':
						$scripts[] = $item['src'];
						break;
				}
			}
		}

		return $scripts;
	}

	/**
	 * @return array
	 */
	public static function selectChoises()
	{
		$options = array_keys(static::$list);

		return array_combine($options, $options);
	}

	/**
	 *
	 * @var string
	 */
	protected $handle = NULL;


	/**
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 *
	 * @var integer
	 */
	private $position = 0;

	/**
	 * @param string $handle
	 */
	public function __construct($handle)
	{
		$this->handle = $handle;
		static::$list[$handle] = $this;
	}

	/**
	 * @param string $handle
	 * @param string $src
	 * @param array|string $deps
	 * @param array $attrs
	 * @return $this
	 */
	public function css($handle = NULL, $src = NULL, $deps = NULL, array $attrs = NULL)
	{
		if ($handle === NULL) {
			$handle = $this->handle;
		}

		// Set default media attribute
		if (!isset($attrs['media'])) {
			$attrs['media'] = 'all';
		}

		$this->data[] = [
			'type' => 'css',
			'src' => $src,
			'deps' => (array)$deps,
			'attrs' => $attrs,
			'handle' => $handle,
			'type' => 'css'
		];

		return $this;
	}

	/**
	 * @param string $handle
	 * @param string $src
	 * @param array $deps
	 * @param bool $footer
	 * @return $this
	 */
	public function js($handle = FALSE, $src = NULL, $deps = NULL, $footer = FALSE)
	{
		if ($handle === NULL) {
			$handle = $this->handle;
		}

		$this->data[] = [
			'type' => 'js',
			'src' => $src,
			'deps' => (array)$deps,
			'footer' => $footer,
			'handle' => $handle,
			'type' => 'js'
		];

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		$string = '';

		foreach ($this->data as $item) {
			switch ($item['type']) {
				case 'css':
					$string .= \HTML::style($item['src'], $item['attrs']);
					break;
				case 'js':
					$string .= \HTML::script($item['src']);
					break;
			}
		}

		return $string;
	}

	function rewind()
	{
		$this->position = 0;
	}

	function current()
	{
		return $this->data[$this->position];
	}

	function key()
	{
		return $this->position;
	}

	function next()
	{
		++$this->position;
	}

	function valid()
	{
		return isset($this->data[$this->position]);
	}
}