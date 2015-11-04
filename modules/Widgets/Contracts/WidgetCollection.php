<?php

namespace KodiCMS\Widgets\Contracts;

use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;

interface WidgetCollection
{
    /**
     * @return array
     */
    public function getRegisteredWidgets();

    /**
     * @param int $id
     *
     * @return Widget|null
     */
    public function getWidgetById($id);

    /**
     * @param string $block
     *
     * @return array
     */
    public function getWidgetsByBlock($block);

    /**
     * @param WidgetInterface $widget
     * @param string          $block
     *
     * @return $this
     */
    public function addWidget(WidgetInterface $widget, $block);

    /**
     * @param integet $id
     *
     * @return bool
     */
    public function removeWidget($id);
}
