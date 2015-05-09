<?php namespace KodiCMS\Widgets\Collection;

use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Widgets\Contracts\WidgetCollection as WidgetCollectionInterface;
use Iterator;

class WidgetCollection implements WidgetCollectionInterface, Iterator {

	/**
	 * @var array
	 */
	protected $registeredWidgets = [];

	/**
	 * @var array
	 */
	protected $layoutBlocks = [];

	/**
	 * @param integer $id
	 * @return Widget
	 */
	public function getWidgetById($id)
	{
		return array_get($this->registeredWidgets, $id);
	}

	/**
	 * @return array
	 */
	public function getRegisteredWidgets()
	{
		return $this->registeredWidgets;
	}

	/**
	 * @param $block
	 * @return array
	 */
	public function getWidgetsByBlock($block)
	{
		$widgets = [];
		foreach($this->registeredWidgets as $id => $widget)
		{
			if($widget->getBlock() != $block) continue;

			$widgets[$id] = $widget;
		}

		return $widgets;
	}

	/**
	 * @return array
	 */
	public function getLayoutBlocks()
	{
		return array_keys($this->layoutBlocks);
	}

	/**
	 * @param WidgetInterface $widget
	 * @param string $block
	 * @return $this
	 */
	public function registerWidget(WidgetInterface $widget, $block)
	{
		$this->registeredWidgets[] = new Widget($widget, $block);

		return $this;
	}

	public function placeWidgetsToLayout()
	{
		$this->sortWidgets();

		foreach ($this->registeredWidgets as $id => $widget)
		{
			if(is_null($widget->getBlock())) continue;

			$this->layoutBlocks[$widget->getBlock()][] = $widget->getObject();
		}
	}

	/**
	 * @return $this
	 */
	protected function sortWidgets()
	{
		$widgets = [];
		$types = ['PRE' => [], '*named' => [], 'POST' => []];

		foreach ($this->registeredWidgets as $id => $widget)
		{
			$block = $widget->getBlock();

			if (array_key_exists($block, $types))
			{
				$types[$block][] = $id;
			}
			else
			{
				$types['*named'][] = $id;
			}
		}

		foreach ($types as $type => $ids)
		{
			foreach ($ids as $id)
			{
				$widgets[$id] = $this->registeredWidgets[$id];
			}
		}

		$this->registeredWidgets = $widgets;

		return $this;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 */
	public function current()
	{
		return current($this->registeredWidgets);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 */
	public function next()
	{
		return next($this->registeredWidgets);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key()
	{
		return key($this->registeredWidgets);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 */
	public function valid()
	{
		return key($this->registeredWidgets) !== null;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 */
	public function rewind()
	{
		return reset($this->registeredWidgets);
	}
}