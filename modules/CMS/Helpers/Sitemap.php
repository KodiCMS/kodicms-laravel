<?php namespace KodiCMS\CMS\Helpers;


class Sitemap implements \RecursiveIterator
{
	private $position = 0;

	/**
	 * Список элементов
	 * @var array
	 */
	protected $elements = [];

	/**
	 *
	 * @param array $array
	 */
	public function __construct(array $array = [])
	{
		$this->elements = $array;
	}

	/**
	 * Поиск страницы по ID
	 *
	 * @param integer $id
	 * @return $this
	 */
	public function find($id)
	{
		$this->elements = $this->_find($this->elements, 'id', $id);

		return $this;
	}

	/**
	 *
	 * @param string $key
	 * @param string $value
	 * @return $this
	 */
	public function findBy($key, $value)
	{
		$this->elements = $this->_find($this->elements, $key, $value);

		return $this;
	}

	/**
	 * Фильтрация массива
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function filter($key, $value)
	{
		$this->_filter($this->elements, $key, $value);

		return $this;
	}

	/**
	 * Получение внутренних страниц относительно текущей
	 * @return $this
	 */
	public function children()
	{
		if (!empty($this->elements[0]['childs'])) {
			$this->elements = $this->elements[0]['childs'];
		} else {
			$this->elements = [];
		}

		return $this;
	}

	/**
	 * Исключение из карты сайта страниц по ID
	 *
	 * @param array $ids
	 * @param boolean $removeChilds
	 * @return $this
	 */
	public function exclude(array $ids, $removeChilds = TRUE)
	{
		if (!empty($ids)) {
			$array = $this->elements;
			$this->_exclude($array, $ids, $removeChilds);
			$this->elements = $array;
			unset($array);
		}

		return $this;
	}

	/**
	 * Вывов спсика страниц в виде массива
	 *
	 * @param boolean $childs Показывать дочерние эелементы
	 * @return array
	 */
	public function asArray($childs = TRUE)
	{
		if ($childs === FALSE) {
			foreach ($this->elements as & $row) {
				if (isset($row['childs'])) {
					unset($row['childs']);
				}
			}
		}

		return $this->elements;
	}

	/**
	 * Сделать список страниц плоским
	 *
	 * @param boolean $childs Показывать дочерние эелементы
	 * @return array
	 */
	public function flatten($childs = TRUE)
	{
		return $this->_flatten($this->elements, $childs);
	}

	/**
	 * Получить хлебные крошки для текущей страницы
	 *
	 * @return array
	 */
	public function breadcrumbs()
	{
		if (isset($this->elements[0])) {
			return array_reverse($this->_breadcrumbs($this->elements[0]));
		}

		return [];
	}

	/**
	 * Получить список страниц для выпадающего списка <select>
	 *
	 * @param string $titleKey
	 * @param boolean $level
	 * @param bool $emptyValue
	 * @return array
	 */
	public function selectChoices($titleKey = 'title', $level = TRUE, $emptyValue = FALSE)
	{
		$array = $this->flatten();

		$options = [];

		if ($emptyValue !== FALSE) {
			$options[] = $emptyValue;
		}

		foreach ($array as $row) {
			if ($level === TRUE) {
				$levelString = str_repeat('- ', array_get($row, 'level', 0) * 2);
			} else {
				$levelString = '';
			}

			$options[$row['id']] = $levelString . $row[$titleKey];
		}

		return $options;
	}

	/**
	 * @param array $array
	 * @param string $key
	 * @param string $value
	 * @return array
	 */
	protected function _find($array, $key, $value)
	{
		$found = [];
		foreach ($array as $row) {
			if ($row[$key] == $value) {
				return [$row];
			}

			if (!empty($row['childs'])) {
				$found = $this->_find($row['childs'], $key, $value);

				if (!empty($found)) {
					return $found;
				}
			}
		}

		return $found;
	}

	/**
	 * @param array $data
	 * @param array $crumbs
	 * @return array
	 */
	protected function _breadcrumbs(array $data, &$crumbs = [])
	{
		$crumbs[] = $data;

		if (!empty($data['parent'])) {
			$this->_breadcrumbs($data['parent'], $crumbs);
		}

		return $crumbs;
	}

	/**
	 * @param array $array
	 * @param array $ids
	 * @param boolean $remove_childs
	 * @return array
	 */
	protected function _exclude(&$array, array $ids, $remove_childs = TRUE)
	{
		foreach ($array as $i => & $row) {
			if (in_array($row['id'], $ids)) {
				unset($array[$i]);

				if ($remove_childs !== TRUE AND !empty($row['childs'])) {
					foreach ($row['childs'] as $child) {
						$array[] = $child;
					}
				}
			}

			if (!empty($row['childs'])) {
				$childs = $row['childs'];
				$this->_exclude($childs, $ids, $remove_childs);
				$row['childs'] = $childs;
				unset($childs);
			}
		}

		return $array;
	}

	/**
	 *
	 * @param array $array
	 * @param string $key
	 * @param mixed $value
	 */
	protected function _filter(& $array, $key, $value)
	{
		foreach ($array as $i => $row) {
			if (isset($row[$key]) AND $row[$key] == $value) {
				unset($array[$i]);
			}
		}
	}

	/**
	 *
	 * @param array $array
	 * @param boolean $childs
	 * @param array $return
	 * @return array
	 */
	protected function _flatten(array $array, $childs = TRUE, & $return = [])
	{
		foreach ($array as $row) {
			$return[$row['id']] = $row;

			if ($childs !== FALSE AND !empty($row['childs'])) {
				$this->_flatten($row['childs'], $childs, $return);
			}

			unset($return[$row['id']]['childs']);
		}

		return $return;
	}

	/*********************************************
	 * RecursiveIterator Methods
	 **********************************************/
	public function hasChildren()
	{
		return is_array($this->elements[$this->position]['childs']);
	}

	public function getChildren()
	{
		return new static($this->elements[$this->position]['childs']);
	}

	public function current()
	{
		return $this->elements[$this->position];
	}

	public function next()
	{
		$this->position++;
	}

	public function key()
	{
		return $this->position;
	}

	public function valid()
	{
		return isset($this->elements[$this->position]);
	}

	public function rewind()
	{
		$this->position = 0;
	}
}