<?php namespace KodiCMS\Widgets\Contracts;

interface WidgetPaginator extends Widget {

	/**
	 * @return int
	 */
	public function getTotalDocuments();
}