<?php

namespace KodiCMS\CMS\Navigation;

interface NavigationSectionInterface extends NavigationPageInterface
{
    /**
     * @return Page[]
     */
    public function getPages();

    /**
     * @return Section[]
     */
    public function getSections();

    /**
     * @param array $pages
     *
     * @return $this
     */
    public function addPages(array $pages);
}
