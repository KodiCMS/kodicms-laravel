<?php

namespace KodiCMS\CMS\Navigation;

interface NavigationPageInterface
{
    /**
     * @return bool
     */
    public function isActive();

    /**
     * @return string
     */
    public function getIcon();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return array
     */
    public function getPermissions();

    /**
     * @param bool $status
     */
    public function setStatus($status = true);

    /**
     * @param Section $section
     *
     * @return $this
     */
    public function setSection(Section &$section);

    /**
     * @return Section
     */
    public function getSection();
}
