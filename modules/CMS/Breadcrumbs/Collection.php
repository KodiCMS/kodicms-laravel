<?php namespace KodiCMS\CMS\Breadcrumbs;

/**
 * Class Manager
 * @package KodiCMS\CMS\Breadcrumbs
 */
class Collection implements \Countable, \Iterator
{
	/**
	 *
	 * @var array
	 */
	protected $options = [
		'view' => 'cms::app.parials.breadcrumbs'
	];

	/**
	 *
	 * @var array
	 */
	protected $items = [];

	/**
	 * @param array $options
	 */
	public function __construct(array $options = [])
	{
		$this->options = $options;
	}

	/**
	 * @return boolean
	 */
	public function isLast()
	{
		$items = $this->items;
		return $this->current() === end($items);
	}

	/**
	 * @return boolean
	 */
	public function isFirst()
	{
		$items = $this->items;
		return $this->current() === reset($items);
	}

	/**
	 * @param string $name
	 * @param bool $url
	 * @param bool|null $isActive
	 * @param integer|null $position
	 * @param array $data
	 * @return $this
	 */
	public function add($name, $url = FALSE, $isActive = NULL, $position = NULL, array $data = [])
	{
		$item = new Item($name, $url, $isActive, $data);
		$position = $this->getNextPosition($position);
		$this->items[$position] = $item;

		return $this;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return Item
	 */
	public function getBy($key, $value)
	{
		$position = $this->findBy($key, $value);

		if ($position === NULL) {
			return NULL;
		}

		return $this->items[$position];
	}


	/**
	 * @param string $key
	 * @param string $value
	 * @return int|null|string
	 */
	public function findBy($key, $value)
	{
		foreach ($this->items as $position => $item) {
			if (is_array($value)) {
				if (in_array($item->{$key}, $value)) {
					return $position;
				}
			} else if ($item->{$key} == $value) {
				return $position;
			}
		}

		return NULL;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @param bool $url
	 * @param bool $isActive
	 * @param integer|null $position
	 * @param array $data
	 * @return $this|bool
	 */
	public function changeBy($key, $value, $url = FALSE, $isActive = NULL, $position = NULL, array $data = [])
	{
		$item = $this->getBy($key, $value);
		if ($item === NULL) {
			return FALSE;
		}

		$item->setUrl($url);

		if (!is_null($isActive)) {
			$item->setActive($isActive);
		}

		if (!empty($data)) {
			$item->setAttribute($data);
		}

		if (!is_null($position)) {
			$position = $this->getNextPosition($position);
			$this->items[$position] = $item;
		}

		return $this;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function deleteByName($name)
	{
		return $this->deleteBy('name', $name);
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	public function deleteBy($key, $value)
	{
		$position = $this->findBy($key, $value);

		if ($position === NULL) {
			return FALSE;
		}

		unset($this->items[$position]);
	}

	/**
	 * @param null|integer $position
	 * @return int
	 */
	protected function getNextPosition($position = NULL)
	{
		$position = (int) $position;

		while (isset($this->items[$position])) {
			$position++;
		}

		return $position;
	}

	public function __unset($name)
	{
		unset($this->items[$name]);
	}

	protected function sort()
	{
		ksort($this->items);
		return $this;
	}

	/**
	 * @return  integer
	 */
	public function count()
	{
		return count($this->items);
	}


	public function rewind()
	{
		reset($this->items);
	}

	public function current()
	{
		$item = current($this->items);

		return $item;
	}

	/**
	 * @return integer
	 */
	public function key()
	{
		$item = key($this->items);

		return $item;
	}

	/**
	 * @return Item
	 */
	public function next()
	{
		$item = next($this->items);
		return $item;
	}

	/**
	 * @return boolean
	 */
	public function valid()
	{
		$key = key($this->items);
		return (!is_null($key) AND $key !== FALSE);
	}

	/**
	 * @return \View
	 */
	public function render()
	{
		$this->sort();

		return view($this->options['view'], [
			'breadcrumbs' => $this
		]);
	}

	public function __toString()
	{
		return (string) $this->render();
	}
}