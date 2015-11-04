<?php

namespace KodiCMS\Datasource\Sections;

use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\SectionToolbarInterface;

class SectionToolbar implements SectionToolbarInterface
{
    /**
     * @var SectionInterface
     */
    protected $section;

    /**
     * @param SectionInterface $section
     */
    public function __construct(SectionInterface $section)
    {
        $this->section = $section;
    }

    /**
     * @param string|null $template
     *
     * @return \Illuminate\View\View
     */
    public function render($template = null)
    {
        if (is_null($template)) {
            if (method_exists($this->section, 'getToolbarTemplate')) {
                $template = $this->section->getToolbarTemplate();
            } else {
                $template = 'datasource::section.toolbar';
            }
        }

        return view($template, [
            'section' => $this->section,
        ]);
    }
}
