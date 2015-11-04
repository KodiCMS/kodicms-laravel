<?php

namespace KodiCMS\Datasource\Datatables;

use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;

class SectionDatatables extends \yajra\Datatables\Datatables
{
    /**
     * @param DocumentInterface $builder
     *
     * @return DatasourceBuilderEngine
     */
    public function usingDatasourceEngine(DocumentInterface $builder, SectionHeadlineInterface $headline)
    {
        return new DatasourceBuilderEngine($builder, $headline, $this->request);
    }
}
