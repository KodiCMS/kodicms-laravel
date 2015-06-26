<?php namespace KodiCMS\Datasource;

use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;

class SectionHeadline implements SectionHeadlineInterface
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

	public function __toString()
	{
		return '';
	}

}