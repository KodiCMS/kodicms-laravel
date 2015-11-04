<?php

namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\CMS\Http\Controllers\API\AbstractFileController;
use KodiCMS\Pages\Model\LayoutBlock;
use KodiCMS\Pages\Model\LayoutCollection;

class LayoutController extends AbstractFileController
{
    /**
     * @return LayoutCollection
     */
    protected function getCollection()
    {
        return new LayoutCollection();
    }

    /**
     * @return string
     */
    protected function getSectionPrefix()
    {
        return 'layout';
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    protected function getRedirectToEditUrl($filename)
    {
        return route('backend.layout.edit', [$filename]);
    }

    public function getRebuildBlocks()
    {
        $layouts = new LayoutCollection;

        $response = [];
        $blocks = [];

        foreach ($layouts as $layout) {
            $blocks[$layout->getKey()] = $layout->findBlocks();
            $response[$layout->getKey()] = view($this->wrapNamespace('layout.partials.blocks'), ['blocks' => $layout->findBlocks()])->render();
        }

        $this->setMessage(trans($this->wrapNamespace('layout.messages.rebuild')));
        $this->setContent($response);
        $this->blocks = $blocks;
    }

    public function getBlocks()
    {
        $layoutName = $this->getParameter('layout', null);
        $blocks = (new LayoutBlock)->getBlocksGroupedByLayouts($layoutName);

        $this->setContent($blocks);
    }
}
