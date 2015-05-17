<?php namespace KodiCMS\Pages\Http\Controllers;

use Assets;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Http\Controllers\System\TemplateController;
use KodiCMS\Pages\Helpers\BlockWysiwyg;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Widgets\Collection\PageWidgetCollection;
use Meta;
use Block;

class PageWysiwygController extends TemplateController
{
	/**
	 * @var bool
	 */
	protected $authRequired = true;

	public function getPageWysiwyg($id)
	{
		Meta::addMeta(['name'    => 'page-id', 'data-id' => $id])
			->addMeta(['name' => 'csrf-token', 'content' => csrf_token()])
			->addPackage(['page-wysiwyg'], true)
			->addToGroup('site-url', '<script type="text/javascript">' . $this->getTemplateScriptsAsString() . '</script>');

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

		$html = $frontendPage->getLayoutView()->render();

		$injectHTML = view('pages::pages.wysiwyg.system_blocks');
		$matches = preg_split('/(<\/body>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		if (count($matches) > 1)
		{
			$html = $matches[0] . $injectHTML->render() . $matches[1] . $matches[2];
		}

		return $html;
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