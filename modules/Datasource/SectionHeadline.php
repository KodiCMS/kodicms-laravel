<?php namespace KodiCMS\Datasource;

use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;

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