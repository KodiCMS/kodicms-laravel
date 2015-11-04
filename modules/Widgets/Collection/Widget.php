<?php

namespace KodiCMS\Widgets\Collection;

use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Widgets\Contracts\WidgetCollectionItem;

class Widget implements WidgetCollectionItem
{
    /**
     * @var WidgetInterface
     */
    protected $widget;

    /**
     * @var string
     */
    protected $block;

    /**
     * @var int
     */
    protected $position;

    /**
     * @param WidgetInterface $widget
     * @param string          $block
     * @param int             $position
     */
    public function __construct(WidgetInterface $widget, $block, $position = 500)
    {
        $this->widget = $widget;
        $this->block = $block;
        $this->position = $position;
    }

    /**
     * @return WidgetInterface
     */
    public function getObject()
    {
        return $this->widget;
    }

    /**
     * @return string
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param $position
     */
    public function setPosition($position)
    {
        $this->position = (int) $position;
    }
}
