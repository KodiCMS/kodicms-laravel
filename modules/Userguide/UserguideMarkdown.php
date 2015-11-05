<?php

namespace KodiCMS\Userguide;

class UserguideMarkdown extends \Parsedown
{
    /**
     * @var  string  base url for links
     */
    public static $baseUrl = '';

    /**
     * Add the current base url to all local links.
     *
     *     [filesystem](about.filesystem "Optional title")
     *
     * @param   string  span text
     *
     * @return  string
     */
    protected function inlineLink($Excerpt)
    {
        $Excerpt['text'] = preg_replace('~(?<!!)(\[.+?\]\()(\/docs\/\{\{version\}\}\/){0,1}(?!\w++://)(?!#)(\S*(?:\s*+".+?")?\))~', '$1'.static::$baseUrl.'/$3', $Excerpt['text']);

        return parent::inlineLink($Excerpt);
    }
}
