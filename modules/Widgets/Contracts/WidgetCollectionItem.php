<?php

namespace KodiCMS\Widgets\Contracts;

interface WidgetCollectionItem
{
    /**
     * @param Widget $widget
     * @param string $block
     */
    public function __construct(Widget $widget, $block);

    /**
     * @return Widget
     */
    public function getObject();

    /**
     * @return string
     */
    public function getBlock();

    /**
     * @return int
     */
    public function getPosition();

    /**
     * @param $position
     */
    public function setPosition($position);
}
