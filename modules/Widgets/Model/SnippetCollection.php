<?php

namespace KodiCMS\Widgets\Model;

use Illuminate\Contracts\View\View;
use KodiCMS\CMS\Model\FileCollection;

class SnippetCollection extends FileCollection
{
    public function __construct()
    {
        return parent::__construct(snippets_path(), 'snippets');
    }

    /**
     * @param string|View $filename
     * @param array       $parameters
     *
     * @return null
     */
    public function findAndRender($filename, array $parameters = [])
    {
        if ($snippet = $this->findFile($filename)) {
            return $snippet->toView($parameters);
        }

        return;
    }
}
