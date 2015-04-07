<?php namespace KodiCMS\Pages\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\CMS\Assets\Core as Assets;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Services\PageUpdator;

class PageController extends BackendController
{
	/**
	 * @var string
	 */
	public $templatePreffix = 'pages::';

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
		Assets::package('backbone');

		$page = $this->getPage($id);
		$this->setTitle(trans('pages::core.title.pages.edit', [
			'title' => $page->title
		]));

		$this->templateScripts['PAGE'] = $page;

		$updator = $page->updatedBy()->first();
		$creator = $page->createdBy()->first();
		$pagesMap = $page->getSitemap();

		$this->setContent('pages.edit', compact('page', 'updator', 'creator', 'pagesMap'));
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
			->with('success', trans('users::core.messages.updated', [$page]));
	}

	public function getCreate($parent_id = NULL)
	{

	}

	public function postCreate($parent_id = NULL)
	{

	}

	public function postDelete()
	{

	}

	/**
	 * @param integer $id
	 * @return Page|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	protected function getPage($id)
	{
		try {
			return Page::findOrFail($id);
		}
		catch (ModelNotFoundException $e) {
			return $this->smartRedirect()->withErrors(trans('pages::core.messages.not_found'));
		}
	}
}