<?php namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Pages\Model\LayoutBlock;
use KodiCMS\Pages\Model\LayoutCollection;

class LayoutController extends Controller
{
	/**
	 * @var bool
	 */
	public $authRequired = TRUE;

	public function getRebuildBlocks()
	{
		$layouts = new LayoutCollection;

		$blocks = [];

		foreach($layouts as $layout)
		{
			$blocks[$layout->getKey()] = $layout->findBlocks();
		}

		$this->setContent($blocks);
	}

	public function getBlocks()
	{
		$layoutName = $this->getParameter('layout', null);
		$blocks = (new LayoutBlock)->getBlocksGroupedByLayouts($layoutName);

		$this->setContent($blocks);
	}
}