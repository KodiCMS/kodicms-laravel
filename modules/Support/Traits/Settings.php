<?php

namespace KodiCMS\Support\Traits;

trait Settings
{
    use ModelSettings;

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getSetting($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setSetting($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->{$this->getSettingsProperty()}[$name]);
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->{$this->getSettingsProperty()}[$name]);
    }

    /**
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setSetting($offset, $value);
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->{$this->getSettingsProperty()}[$offset]);
    }

    /**
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->{$this->getSettingsProperty()}[$offset]);
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getSetting($offset);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getSettings();
    }
}
