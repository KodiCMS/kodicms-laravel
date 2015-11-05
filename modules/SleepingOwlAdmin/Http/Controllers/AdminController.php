<?php

namespace KodiCMS\SleepingOwlAdmin\Http\Controllers;

use App;
use Input;
use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Renderable;
use KodiCMS\SleepingOwlAdmin\Interfaces\FormInterface;
use KodiCMS\SleepingOwlAdmin\Model\ModelConfiguration;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class AdminController extends BackendController
{
    public function before()
    {
        parent::before();
        // TODO: изменить порядок
        $this->breadcrumbs->add('Sleeping Owl Admin', false, true);
    }

    /**
     * @param ModelConfiguration $model
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDisplay(ModelConfiguration $model)
    {
        return $this->render($model->title(), $model->display());
    }

    /**
     * @param ModelConfiguration $model
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate(ModelConfiguration $model)
    {
        $create = $model->create();
        if (is_null($create)) {
            abort(404);
        }

        return $this->render($model->title(), $create);
    }

    /**
     * @param ModelConfiguration $model
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postStore(ModelConfiguration $model)
    {
        $create = $model->create();
        if (is_null($create)) {
            abort(404);
        }
        if ($create instanceof FormInterface) {
            if ($validator = $create->validate($model)) {
                return redirect()->back()->withErrors($validator)->withInput()->with([
                    '_redirectBack' => Input::get('_redirectBack'),
                ]);
            }
            $create->save($model);
        }

        return redirect()->to(Input::get('_redirectBack', $model->displayUrl()));
    }

    /**
     * @param ModelConfiguration $model
     * @param int                $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit(ModelConfiguration $model, $id)
    {
        $edit = $model->fullEdit($id);
        if (is_null($edit)) {
            abort(404);
        }

        return $this->render($model->title(), $edit);
    }

    /**
     * @param ModelConfiguration $model
     * @param int                $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdate(ModelConfiguration $model, $id)
    {
        $edit = $model->fullEdit($id);
        if (is_null($edit)) {
            abort(404);
        }
        if ($edit instanceof FormInterface) {
            if ($validator = $edit->validate($model)) {
                return redirect()->back()->withErrors($validator)->withInput()->with([
                    '_redirectBack' => Input::get('_redirectBack'),
                ]);
            }
            $edit->save($model);
        }

        return redirect()->to(Input::get('_redirectBack', $model->displayUrl()));
    }

    /**
     * @param ModelConfiguration $model
     * @param int                $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDestroy(ModelConfiguration $model, $id)
    {
        $delete = $model->delete($id);
        if (is_null($delete)) {
            abort(404);
        }
        $model->repository()->delete($id);

        return redirect()->back();
    }

    /**
     * @param ModelConfiguration $model
     * @param int                $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRestore($model, $id)
    {
        $restore = $model->restore($id);
        if (is_null($restore)) {
            abort(404);
        }
        $model->repository()->restore($id);

        return redirect()->back();
    }

    /**
     * @param string            $title
     * @param Renderable|string $content
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render($title, $content)
    {
        if ($content instanceof Renderable) {
            $content = $content->render();
        }

        $this->template->with('content', $content);
    }

    /**
     * @return Response
     */
    public function getLang()
    {
        $lang = trans('admin::lang');
        if ($lang == 'admin::lang') {
            $lang = trans('admin::lang', [], 'messages', 'en');
        }

        $data = [
            'locale'       => App::getLocale(),
            'token'        => csrf_token(),
            'prefix'       => config('admin.prefix'),
            'lang'         => $lang,
            'ckeditor_cfg' => config('admin.ckeditor'),
        ];

        $content = 'window.admin = '.json_encode($data).';';

        $response = new Response($content, 200, [
            'Content-Type' => 'text/javascript',
        ]);

        return $this->cacheResponse($response);
    }

    /**
     * @param Response $response
     *
     * @return Response
     */
    protected function cacheResponse(Response $response)
    {
        $response->setSharedMaxAge(31536000);
        $response->setMaxAge(31536000);
        $response->setExpires(new \DateTime('+1 year'));

        return $response;
    }

    public function getWildcard()
    {
        abort(404);
    }
}
