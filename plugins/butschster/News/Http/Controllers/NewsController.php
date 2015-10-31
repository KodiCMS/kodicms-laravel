<?php
namespace Plugins\butschster\News\Http\Controllers;

use WYSIWYG;
use Plugins\butschster\News\Model\News;
use Plugins\butschster\News\Repository\NewsRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class NewsController extends BackendController
{

    /**
     * @var string
     */
    public $moduleNamespace = 'butschster:news::';


    /**
     * @param NewsRepository $repository
     */
    public function getIndex(NewsRepository $repository)
    {
        $newsList = $repository->paginate();
        $this->setContent('news.index', compact('newsList'));
    }


    /**
     * @param NewsRepository $repository
     * @param integer        $id
     */
    public function getEdit(NewsRepository $repository, $id)
    {
        $news = $repository->findOrFail($id);
        $this->setTitle(trans('butschster:news::core.title.edit', [
            'title' => $news->title,
        ]));

        $this->templateScripts['NEWS'] = $news;
        $this->setContent('news.edit', compact('news'));
    }


    /**
     * @param NewsRepository $repository
     * @param integer        $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(NewsRepository $repository, $id)
    {
        $data = $this->request->all();
        $repository->validateOnUpdate($id, $data);
        $news = $repository->update($id, $data);

        return $this->smartRedirect([$news])
            ->with('success', trans('butschster:news::core.messages.updated', [
                'title' => $news->title
            ]));
    }


    /**
     * @param NewsRepository $repository
     */
    public function getCreate(NewsRepository $repository)
    {
        $news = $repository->instance();
        $this->setTitle(trans('butschster:news::core.title.create'));
        $this->setContent('news.create', compact('news'));
    }


    /**
     * @param NewsRepository $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(NewsRepository $repository)
    {
        $data = $this->request->all();
        $repository->validateOnCreate($data);
        $news = $repository->create($data);

        return $this->smartRedirect([$news])
            ->with('success', trans('butschster:news::core.messages.created', [
                'title' => $news->title
            ]));
    }


    /**
     * @param NewsRepository $repository
     * @param integer        $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete(NewsRepository $repository, $id)
    {
        $news = $repository->delete($id);

        return $this->smartRedirect()
            ->with('success', trans('butschster:news::core.messages.deleted', [
                'title' => $news->title
            ]));
    }
}