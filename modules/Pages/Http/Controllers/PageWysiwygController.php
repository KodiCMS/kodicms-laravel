<?php namespace KodiCMS\Pages\Http\Controllers;

use Assets;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Controller;
use KodiCMS\Pages\Helpers\BlockWysiwyg;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Widgets\Collection\PageWidgetCollection;
use Meta;
use Block;

class PageWysiwygController extends Controller
{
	public function getPageWysiwyg($id)
	{
		Meta::addMeta([
			'name'    => 'page-id',
			'data-id' => $id
		])
			->addMeta([
				'name' => 'csrf-token',
				'content' => csrf_token(),
			])
			->addCss('fancy', resources_url() . '/libs/fancybox/jquery.fancybox.css')
			->addPackage([
				'jquery',
				'sortable',
				'page-wysiwyg',
				'libraries',
				'core',
			])
			->addToGroup('site-url', '<script type="text/javascript">var SITE_URL="' . url() . '";</script>');

		$page = $this->getPage($id);
		$frontendPage = new FrontendPage($page->toArray());

		app()->singleton('frontpage', function () use ($frontendPage)
		{
			return $frontendPage;
		});

		app()->singleton('layout.widgets', function () use ($frontendPage)
		{
			return new PageWidgetCollection($frontendPage->getId());
		});

		app()->singleton('layout.block', function () use ($page)
		{
			return new BlockWysiwyg(app('layout.widgets'), $page);
		});

		Block::run('-1');
		Block::run('0');

		return $frontendPage->getLayoutView()->render();
	}

	protected function getPage($id)
	{
		try
		{
			return Page::findOrFail($id);
		}
		catch (ModelNotFoundException $e)
		{
			$this->throwFailException($this->smartRedirect()->withErrors(trans('pages::core.messages.not_found')));
		}
	}

} 