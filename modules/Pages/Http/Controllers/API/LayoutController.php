<?php namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\CMS\Http\Controllers\API\AbstractFileController;
use KodiCMS\Pages\Model\LayoutBlock;
use KodiCMS\Pages\Model\LayoutCollection;

class LayoutController extends AbstractFileController
{
	/**
	 * @var bool
	 */
	public $authRequired = TRUE;

	/**
	 * @var string
	 */
	public $moduleNamespace = 'pages::';

	/**
	 * @return LayoutCollection
	 */
	protected function getCollection()
	{
		return new LayoutCollection();
	}

	/**
	 * @return string
	 */
	protected function getSectionPrefix()
	{
		return 'layout';
	}

	/**
	 * @param string $filename
	 * @return string
	 */
	protected function getRedirectToEditUrl($filename)
	{
		return route('backend.layout.edit', [$filename]);
	}

	public function getRebuildBlocks()
	{
		$layouts = new LayoutCollection;

		$blocks = [];

		foreach($layouts as $layout)
		{
			$blocks[$layout->getKey()] = view('pages::layout.partials.blocks', ['blocks' => $layout->findBlocks()])->render();
		}

		$this->setMessage(trans('pages::layout.messages.rebuild'));
		$this->setContent($blocks);
	}

	public function getBlocks()
	{
		$layoutName = $this->getParameter('layout', null);
		$blocks = (new LayoutBlock)->getBlocksGroupedByLayouts($layoutName);

		$this->setContent($blocks);
	}
}