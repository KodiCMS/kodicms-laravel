<?php

namespace KodiCMS\Pages\Listeners;

use Block;
use KodiCMS\Pages\PagePart;
use KodiCMS\Pages\Model\LayoutBlock;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Widget\PagePart as PagePartWidget;

class PlacePagePartsToBlocksEventHandler
{
    /**
     * Handle the event.
     *
     * @param FrontendPage $page
     */
    public function handle(FrontendPage $page)
    {
        $layoutBlocks = (new LayoutBlock)->getBlocksGroupedByLayouts($page->getLayout());

        foreach ($layoutBlocks as $name => $blocks) {
            foreach ($blocks as $block) {
                if (! ($part = PagePart::exists($page, $block))) {
                    continue;
                }

                $partWidget = new PagePartWidget($part['name']);
                $partWidget->setContent($part['content_html']);
                Block::addWidget($partWidget, $block);
            }
        }
    }
}
