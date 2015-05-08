<?php namespace KodiCMS\Widgets\Contracts;

interface WidgetPaginator extends WidgetRenderable {

	/**
	 * @return int
	 */
	public function getTotalDocuments();
}