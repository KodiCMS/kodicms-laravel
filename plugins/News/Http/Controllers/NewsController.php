<?php namespace Plugins\News\Http\Controllers;

use WYSIWYG;
use Plugins\News\Model\News;
use Plugins\News\Services\NewsCreator;
use Plugins\News\Services\NewsUpdator;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class NewsController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'news::';

	public function getIndex()
	{
		$newsList = News::paginate();

		$this->setContent('news.index', compact('newsList'));
	}

	public function getEdit($id)
	{
		WYSIWYG::loadDefaultEditors();

		$news = $this->getNews($id);
		$this->setTitle(trans('news::core.title.edit', [
			'title' => $news->title
		]));

		$this->templateScripts['NEWS'] = $news;

		$this->setContent('news.edit', compact('news'));
	}

	public function postEdit(NewsUpdator $newsUpdator, $id)
	{
		$data = $this->request->all();
		$validator = $newsUpdator->validator($id, $data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$news = $newsUpdator->update($id, $data);

		return $this->smartRedirect([$news])
			->with('success', trans('news::core.messages.updated', ['title' => $news->title]));
	}

	public function getCreate()
	{
		WYSIWYG::loadDefaultEditors();

		$news = new News();

		$this->setTitle(trans('news::core.title.create'));
		$this->setContent('news.create', compact('news'));
	}

	public function postCreate(NewsCreator $newsCreator)
	{
		$data = $this->request->all();

		$validator = $newsCreator->validator($data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$news = $newsCreator->create($data);

		return $this->smartRedirect([$news])
			->with('success', trans('news::core.messages.created', ['title' => $news->title]));
	}

	public function getDelete($id)
	{
		$news = $this->getNews($id);

		$news->delete();

		return $this->smartRedirect()->with('success', trans('news::core.messages.deleted', ['title' => $news->title]));
	}

	/**
	 * @param integer $id
	 * @return Page
	 * @throws HttpResponseException
	 */
	protected function getNews($id)
	{
		try
		{
			return News::findOrFail($id);
		}
		catch (ModelNotFoundException $e)
		{
			$this->throwFailException($this->smartRedirect()->withErrors(trans('news::core.messages.not_found')));
		}
	}
}