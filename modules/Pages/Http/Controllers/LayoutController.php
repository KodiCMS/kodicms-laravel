<?php namespace KodiCMS\Pages\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\CMS\Assets\Core as Assets;
use KodiCMS\Pages\Model\LayoutCollection;

class LayoutController extends BackendController
{
	/**
	 * @var string
	 */
	public $templatePrefix = 'pages::';

	public function getIndex()
	{
		$collection = new LayoutCollection();

		$this->setContent('layouts.list', compact('collection'));
	}

	public function getCreate()
	{
		$layout = $this->getFile();

		$this->setTitle(trans('pages::layout.title.create'));

		$this->setContent('layouts.create', compact('layout'));
	}

	public function postCreate()
	{

	}

	public function getEdit($filename)
	{
		$layout = $this->getFile($filename);

		$this->setTitle(trans('pages::layout.title.edit', [
			'name' => $layout->getName()
		]));

		$this->setContent('layouts.edit', compact('layout'));
	}

	public function postEdit($filename)
	{

	}

	public function getDelete($filename)
	{

	}

	public function getFile($filename = NULL)
	{
		Assets::package('ace');

		if(is_null($filename))
		{
			return (new LayoutCollection())->newFile();
		}

		if($file = (new LayoutCollection())->findFile($filename))
		{
			return $file;
		}

		$this->throwFailException($this->smartRedirect()->withErrors(trans('pages::layout.messages.not_found')));
	}
}