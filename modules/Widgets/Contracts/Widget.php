<?php

namespace KodiCMS\Widgets\Contracts;

interface Widget
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     *
     * @throws WidgetException
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getSettingsTemplate();

    /**
     * @return array
     */
    public function prepareSettingsData();

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function setSetting($name, $value = null);

    /**
     * @param array $settings
     *
     * @return $this
     */
    public function setSettings(array $settings);

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function getSetting($name, $default = null);

    /**
     * @return array
     */
    public function getSettings();

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters);

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function setParameter($name, $value = null);

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function getParameter($name, $default = null);
}
