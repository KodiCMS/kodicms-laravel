<?php

namespace KodiCMS\SleepingOwlAdmin\Filter;

class FilterField extends FilterBase
{
    /**
     * @param string|null $title
     *
     * @return $this|mixed|string
     */
    public function title($title = null)
    {
        $parent = parent::title($title);
        if (is_null($parent)) {
            return $this->value();
        }

        return $parent;
    }
}
