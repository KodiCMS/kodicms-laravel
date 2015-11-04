<?php

namespace KodiCMS\Email\Http\Controllers;

use WYSIWYG;
use KodiCMS\Email\Model\EmailTemplate;
use KodiCMS\Email\Repository\EmailEventRepository;
use KodiCMS\Email\Repository\EmailTemplateRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class EmailTemplateController extends BackendController
{
    /**
     * @param EmailTemplateRepository $repository
     */
    public function getIndex(EmailTemplateRepository $repository)
    {
        $emailTemplates = $repository->paginate();
        $this->setContent('email.template.list', compact('emailTemplates'));
    }

    /**
     * @param EmailTemplateRepository $repository
     * @param EmailEventRepository    $emailEventRepository
     */
    public function getCreate(EmailTemplateRepository $repository, EmailEventRepository $emailEventRepository)
    {
        WYSIWYG::loadDefaultEditors();
        $this->setTitle(trans('email::core.title.templates.create'));

        $emailTemplate = $repository->instance([
            'message_type'  => EmailTemplate::TYPE_HTML,
            'subject'       => '{site_title}',
            'email_from'    => '{default_email}',
            'email_to'      => '{email_to}',
            'email_type_id' => $this->request->get('email_type_id'),
        ]);

        $action = 'backend.email.template.create.post';
        $emailEvents = $emailEventRepository->eventsList();

        $this->setContent('email.template.form', compact('emailTemplate', 'action', 'emailEvents'));
    }

    /**
     * @param EmailTemplateRepository $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(EmailTemplateRepository $repository)
    {
        $data = $this->request->all();
        $repository->validate($data);
        $emailTemplate = $repository->create($data);

        return $this->smartRedirect([$emailTemplate])
            ->with('success', trans('email::core.messages.templates.created', [
                'title' => $emailTemplate->subject,
            ]));
    }

    /**
     * @param EmailTemplateRepository $repository
     * @param EmailEventRepository    $emailEventRepository
     * @param int                 $id
     */
    public function getEdit(EmailTemplateRepository $repository, EmailEventRepository $emailEventRepository, $id)
    {
        WYSIWYG::loadDefaultEditors();
        $emailTemplate = $repository->findOrFail($id);

        $this->setTitle(trans('email::core.title.templates.edit', [
            'title' => $emailTemplate->subject,
        ]));
        $action = 'backend.email.template.edit.post';

        $emailEvents = $emailEventRepository->eventsList();

        $this->setContent('email.template.form', compact('emailTemplate', 'action', 'emailEvents'));
    }

    /**
     * @param EmailTemplateRepository $repository
     * @param int                 $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(EmailTemplateRepository $repository, $id)
    {
        $data = $this->request->all();

        $repository->validate($data);
        $emailTemplate = $repository->update($id, $data);

        return $this->smartRedirect([$emailTemplate])
            ->with('success', trans('email::core.messages.templates.updated', [
                'title' => $emailTemplate->subject,
            ]));
    }

    /**
     * @param EmailTemplateRepository $repository
     * @param int                 $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postDelete(EmailTemplateRepository $repository, $id)
    {
        $emailTemplate = $repository->delete($id);

        return $this->smartRedirect()
            ->with('success', trans('email::core.messages.templates.deleted', [
                'title' => $emailTemplate->subject,
            ]));
    }
}
