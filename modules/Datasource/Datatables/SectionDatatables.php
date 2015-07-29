<?php namespace KodiCMS\Datasource\Datatables;

use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;
use KodiCMS\Datasource\Sections\Document;

class SectionDatatables extends \yajra\Datatables\Datatables
{
	/**
	 * @param Document $builder
	 *
	 * @return DatasourceBuilderEngine
	 */
	public function usingDatasourceEngine(Document $builder, SectionHeadlineInterface $headline)
	{
		return new DatasourceBuilderEngine($builder, $headline, $this->request);
	}
}