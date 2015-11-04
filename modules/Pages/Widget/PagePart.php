<?php

namespace KodiCMS\Pages\Widget;

use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\Helpers\ViewPHP;
use KodiCMS\Widgets\Traits\WidgetRender;
use KodiCMS\Widgets\Widget\Decorator;

class PagePart extends Decorator implements WidgetRenderable
{
    use WidgetRender;

    /**
     * @var string
     */
    protected $html = '';

    /**
     * @return array
     */
    public function prepareData()
    {
        return [

        ];
    }

    /**
     * @param string $html
     */
    public function setContent($html)
    {
        $this->html = $html;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'PagePart::'.parent::getName();
    }

    /**
     * @return string|View
     */
    public function getFrontendTemplate()
    {
        return new ViewPHP($this->html);
    }
}
