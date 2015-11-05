<?php

namespace KodiCMS\Support\Traits;

trait ModelSettings
{
    /**
     * @return array
     */
    public function booleanSettings()
    {
        return [];
    }

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->{$this->getSettingsProperty()};
    }

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function getSetting($name, $default = null)
    {
        $method = 'getSetting'.studly_case($name);

        if (is_null($default)) {
            $default = array_get($this->defaultSettings(), $name);
        }

        if (method_exists($this, $method)) {
            return $this->{$method}($default);
        }

        return array_get($this->{$this->getSettingsProperty()}, $name, $default);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function setSetting($name, $value = null)
    {
        if (is_array($name)) {
            $this->setSettings($name);
        } else {
            $method = 'setSetting'.studly_case($name);
            if (method_exists($this, $method)) {
                return $this->{$method}($value);
            } else {
                if (array_key_exists($name, $this->booleanSettings())) {
                    $value = ! empty($value) ? true : false;
                }

                $this->{$this->getSettingsProperty()}[$name] = $value;
            }
        }

        return $this;
    }

    /**
     * @param array $settings
     *
     * @return $this
     */
    public function setSettings(array $settings)
    {
        foreach ($settings as $key => $value) {
            $this->setSetting($key, $value);
        }

        return $this;
    }

    /**
     * @param array $settings
     *
     * @return $this
     */
    public function replaceSettings(array $settings)
    {
        $this->{$this->getSettingsProperty()} = [];

        return $this->setSettings($settings);
    }

    /**
     * @return string
     */
    protected function getSettingsProperty()
    {
        return 'settings';
    }
}
