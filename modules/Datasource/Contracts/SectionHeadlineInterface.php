<?php namespace KodiCMS\Datasource\Contracts;

interface SectionHeadlineInterface
{
	/**
	 * @return array
	 */
	public function getHeadlineFields();

	/**
	 * @return array
	 */
	public function getActiveFieldIds();

	/**
	 * @return array
	 */
	public function getSearchableFields();

	/**
	 * @return array
	 */
	public function getDocuments();

	/**
	 * @param string|null $template
	 *
	 * @return \Illuminate\View\View
	 */
	public function render($template = null);
}