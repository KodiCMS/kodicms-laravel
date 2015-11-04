<?php

namespace KodiCMS\Widgets\Traits;

trait WidgetPaginator
{
    /**
     * @param int $offset
     */
    public function setSettingListOffset($offset)
    {
        $this->settings['list_offset'] = (int) $offset;
    }

    /**
     * @param int $size
     */
    public function setSettingListSize($size)
    {
        $this->settings['list_size'] = (int) $size;
    }

    /**
     * @param int $default
     *
     * @return int
     */
    public function getSettingListSize($default = 10)
    {
        $size = array_get($this->settings, 'list_size', $default);

        return $size == 0 ? 10 : $size;
    }

    /**
     * @param int $default
     *
     * @return int
     */
    public function getSettingListOffset($default = 0)
    {
        return (int) array_get($this->settings, 'list_offset', $default);
    }
}
