<?php namespace KodiCMS\Pages\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Helpers\WYSIWYG;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use Assets;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use KodiCMS\Pages\Helpers\BlockWysiwyg;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Services\PageCreator;
use KodiCMS\Pages\Services\PageUpdator;
use KodiCMS\Widgets\Collection\PageWidgetCollection;

class PageController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'pages::';

	/**
	 * @var array
	 */
	public $allowedActions = ['children'];

	public function getIndex()
	{
		Assets::package(['nestable', 'editable']);

		$this->templateScripts['PAGE_STATUSES'] = array_map(function ($value, $key) {
			return ['id' => $key, 'text' => $value];
		}, Page::getStatusList(), array_keys(Page::getStatusList()));

		$page = $this->getPage(1);

		$this->setContent('pages.index', compact('page'));
	}

	public function getEdit($id)
	{
		Assets::package(['backbone', 'jquery-ui']);
		WYSIWYG::loadAll();

		$page = $this->getPage($id);
		$this->setTitle(trans('pages::core.title.pages.edit', [
			'title' => $page->title
		]));

		$this->templateScripts['PAGE'] = $page;

		$updator = $page->updatedBy()->first();
		$creator = $page->createdBy()->first();
		$pagesMap = $page->getSitemap();

		$page->setAppends(['layout']);

		$behaviorList = BehaviorManager::formChoices();

		$this->setContent('pages.edit', compact('page', 'updator', 'creator', 'pagesMap', 'behaviorList'));
	}

	public function postEdit(PageUpdator $page, $id)
	{
		$data = $this->request->all();
		$validator = $page->validator($id, $data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$page = $page->update($id, $data);

		return $this->smartRedirect([$page])
			->with('success', trans('pages::core.messages.updated', ['title' => $page->title]));
	}

	public function getCreate($parent_id = NULL)
	{
		$page = new Page([
			'parent_id' => $parent_id,
			'published_at' => new Carbon
		]);
		$this->setTitle(trans('pages::core.title.pages.create'));

		$pagesMap = $page->getSitemap();
		$behaviorList = BehaviorManager::formChoices();

		$this->setContent('pages.create', compact('page', 'pagesMap', 'behaviorList'));
	}

	public function postCreate(PageCreator $page, $parent_id = NULL)
	{
		$data = $this->request->all();

		$validator = $page->validator($data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$page = $page->create($data);

		return $this->smartRedirect([$page])
			->with('success', trans('pages::core.messages.created', ['title' => $page->title]));
	}

	public function getDelete($id)
	{
		$page = $this->getPage($id);
		$page->delete();

		return $this->smartRedirect()
			->with('success', trans('pages::core.messages.deleted', ['title' => $page->title]));
	}

	/**
	 * @param integer $id
	 * @return Page
	 * @throws HttpResponseException
	 */
	protected function getPage($id)
	{
		try {
			return Page::findOrFail($id);
		}
		catch (ModelNotFoundException $e) {
			$this->throwFailException($this->smartRedirect()->withErrors(trans('pages::core.messages.not_found')));
		}
	}

}