<?php

namespace KodiCMS\CMS\Contracts;

interface WysiwygFilterInterface
{
    /**
     * @param string $text
     *
     * @return string
     */
    public function apply($text);
}
