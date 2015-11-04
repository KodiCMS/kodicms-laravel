<?php

namespace KodiCMS\CMS\Wysiwyg;

use Blade;
use KodiCMS\CMS\Contracts\WysiwygFilterInterface;

class WysiwygDummyFilter implements WysiwygFilterInterface
{
    /**
     * @param string $text
     *
     * @return string
     */
    public function apply($text)
    {
        return Blade::compileString(
            preg_replace(['/<(\?|\%)\=?(php)?/', '/(\%|\?)>/'], ['', ''], $text)
        );
    }
}
