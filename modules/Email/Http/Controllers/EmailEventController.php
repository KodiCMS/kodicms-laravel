<?php

namespace KodiCMS\Email\Http\Controllers;

use KodiCMS\Email\Repository\EmailEventRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class EmailEventController extends BackendController
{
    /**
     * @param EmailEventRepository $repository
     */
    public function getIndex(EmailEventRepository $repository)
    {
        $emailEvents = $repository->paginate();
        $this->setContent('email.event.list', compact('emailEvents'));
    }

    /**
     * @param EmailEventRepository $repository
     */
    public function getCreate(EmailEventRepository $repository)
    {
        $this->setTitle(trans('email::core.title.events.create'));

        $emailEvent = $repository->instance();
        $action = 'backend.email.event.create.post';

        $this->setContent('email.event.form', compact('emailEvent', 'action'));
    }

    /**
     * @param EmailEventRepository $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(EmailEventRepository $repository)
    {
        $data = $this->request->all();
        $this->formatFields($data);
        $repository->validateOnCreate($data);

        $emailEvent = $repository->create($data);

        return $this->smartRedirect([$emailEvent])
            ->with('success', trans('email::core.messages.events.created', [
                'title' => $emailEvent->name,
            ]));
    }

    /**
     * @param EmailEventRepository $repository
     * @param                      $id
     */
    public function getEdit(EmailEventRepository $repository, $id)
    {
        $emailEvent = $repository->findOrFail($id);

        $this->setTitle(trans('email::core.title.events.edit', [
            'title' => $emailEvent->name,
        ]));

        $action = 'backend.email.event.edit.post';
        $this->setContent('email.event.form', compact('emailEvent', 'action'));
    }

    /**
     * @param EmailEventRepository $repository
     * @param int              $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(EmailEventRepository $repository, $id)
    {
        $data = $this->request->all();
        $this->formatFields($data);
        $repository->validateOnUpdate($data);

        $emailEvent = $repository->update($id, $data);

        return $this->smartRedirect([$emailEvent])
            ->with('success', trans('email::core.messages.events.updated', [
                'title' => $emailEvent->name,
            ]));
    }

    /**
     * @param EmailEventRepository $repository
     * @param int              $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postDelete(EmailEventRepository $repository, $id)
    {
        $emailEvent = $repository->delete($id);

        return $this->smartRedirect()
            ->with('success', trans('email::core.messages.events.deleted', [
                'title' => $emailEvent->name,
            ]));
    }

    /**
     * @param array $data
     */
    protected function formatFields(&$data)
    {
        $fields = array_get($data, 'fields', []);

        $keys = array_get($fields, 'key', []);
        $names = array_get($fields, 'value', []);
        $value = array_combine($keys, $names);
        $value = array_unique(array_filter($value));
        array_set($data, 'fields', $value);
    }
}
