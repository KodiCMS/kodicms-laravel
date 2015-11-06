<?php

namespace KodiCMS\SleepingOwlAdmin\Interfaces;

interface NamedColumnInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);
}
