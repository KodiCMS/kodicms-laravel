<?php

namespace KodiCMS\Widgets\Collection;

use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Widgets\Contracts\WidgetCollection as WidgetCollectionInterface;
use Iterator;

class WidgetCollection implements WidgetCollectionInterface, Iterator
{
    /**
     * @var array
     */
    protected $registeredWidgets = [];

    /**
     * @var array
     */
    protected $layoutBlocks = [];

    /**
     * @param int $id
     *
     * @return Widget|null
     */
    public function getWidgetById($id)
    {
        foreach ($this->registeredWidgets as $widget) {
            if ($widget->getObject()->getId() == $id) {
                return $widget;
            }
        }

        return;
    }

    /**
     * @return array
     */
    public function getRegisteredWidgets()
    {
        return $this->registeredWidgets;
    }

    /**
     * @param string $block
     *
     * @return array
     */
    public function getWidgetsByBlock($block)
    {
        $widgets = [];
        foreach ($this->registeredWidgets as $widget) {
            if ($widget->getBlock() != $block) {
                continue;
            }

            $widgets[] = $widget;
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
     * @param string          $block
     *
     * @return $this
     */
    public function addWidget(WidgetInterface $widget, $block, $position = 500)
    {
        $this->registeredWidgets[] = new Widget($widget, $block, $position);

        return $this;
    }

    /**
     * @param integet $id
     *
     * @return bool
     */
    public function removeWidget($id)
    {
        foreach ($this->registeredWidgets as $i => $widget) {
            if ($widget->getObject()->getId() == $id) {
                unset($this->registeredWidgets[$i]);

                return true;
            }
        }

        return false;
    }

    /**
     * @return void
     */
    public function placeWidgetsToLayoutBlocks()
    {
        $this->sortWidgets();

        foreach ($this->registeredWidgets as $widget) {
            if (is_null($widget->getBlock())) {
                continue;
            }

            $this->layoutBlocks[$widget->getBlock()][$widget->getPosition()] = $widget->getObject();
        }

        foreach ($this->registeredWidgets as $widget) {
            if (method_exists($widget->getObject(), 'onLoad')) {
                app()->call([$widget->getObject(), 'onLoad']);
            }
        }

        foreach ($this->registeredWidgets as $widget) {
            if (method_exists($widget->getObject(), 'afterLoad')) {
                app()->call([$widget->getObject(), 'afterLoad']);
            }
        }
    }

    /**
     * @return $this
     */
    protected function sortWidgets()
    {
        $types = ['PRE' => [], '*named' => [], 'POST' => []];

        uasort($this->registeredWidgets, function ($a, $b) {
            if ($a->getPosition() == $b->getPosition()) {
                return 0;
            }

            return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
        });

        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element.
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->registeredWidgets);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element.
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        return next($this->registeredWidgets);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element.
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->registeredWidgets);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid.
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return key($this->registeredWidgets) !== null;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element.
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return reset($this->registeredWidgets);
    }
}
