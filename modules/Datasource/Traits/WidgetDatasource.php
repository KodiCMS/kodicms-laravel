<?php

namespace KodiCMS\Datasource\Traits;

use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Repository\SectionRepository;

trait WidgetDatasource
{
    /**
     * @var SectionInterface|null
     */
    protected $section;

    /**
     * @param SectionRepository $repository
     */
    public function boot(SectionRepository $repository)
    {
        $this->sectionRepository = $repository;
    }

    /**
     * @return array
     */
    public function getAllowedSectionTypes()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isDatasourceSelected()
    {
        return $this->getSectionId() > 0;
    }

    /**
     * @return int
     */
    public function getSectionId()
    {
        return (int) $this->section_id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|SectionInterface|null
     */
    public function getSection()
    {
        if (is_null($this->section) and $this->isDatasourceSelected()) {
            $this->section = $this->sectionRepository->findOrFail($this->getSectionId());
        }

        return $this->section;
    }
}
