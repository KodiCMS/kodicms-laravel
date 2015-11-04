<?php

namespace KodiCMS\CMS\Contracts;

interface WysiwygEditorInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return WysiwygFilterInterface
     */
    public function getFilter();

    /**
     * @return bool
     */
    public function isUsed();

    /**
     * @param string $text
     *
     * @return string
     */
    public function applyFilter($text);

    /**
     * @return bool
     */
    public function load();
}
