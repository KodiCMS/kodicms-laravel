<?php

namespace KodiCMS\CMS\Navigation;

class Page extends ItemDecorator
{
    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function __set($name, $value)
    {
        parent::__set($name, $value);

        if (! is_null($this->sectionObject)) {
            $this->getSection()->update();
        }

        return $this;
    }
}
