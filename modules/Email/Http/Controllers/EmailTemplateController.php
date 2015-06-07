<?php namespace KodiCMS\Email\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\Email\Repository\EmailEventRepository;
use KodiCMS\Email\Repository\EmailTemplateRepository;
use WYSIWYG;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Email\Model\EmailTemplate;
use KodiCMS\Email\Model\EmailType;
use KodiCMS\Email\Services\EmailTemplateCreator;
use KodiCMS\Email\Services\EmailTemplateUpdator;

class EmailTemplateController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'email::';

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
	 * @param EmailEventRepository $emailEventRepository
	 */
	public function getCreate(EmailTemplateRepository $repository, EmailEventRepository $emailEventRepository)
	{
		WYSIWYG::loadAll();
		$this->setTitle(trans('email::core.title.templates.create'));

		$emailTemplate = $repository->instance();

		$emailTemplate->message_type = EmailTemplate::TYPE_HTML;
		$emailTemplate->subject = '{site_title}';
		$emailTemplate->email_from = '{default_email}';
		$emailTemplate->email_to = '{email_to}';
		$emailTemplate->email_type_id = $this->request->get('email_type_id');

		$action = 'backend.email.template.create.post';

		$emailEvents = $emailEventRepository->eventsList();

		$this->setContent('email.template.form', compact('emailTemplate', 'action', 'emailEvents'));
	}

	/**
	 * @param EmailTemplateRepository $repository
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postCreate(EmailTemplateRepository $repository)
	{
		$data = $this->request->all();

		$validator = $repository->validator($data);

		if ($validator->fails())
		{
			$this->throwValidationException($this->request, $validator);
		}

		$emailTemplate = $repository->create($data);

		return $this->smartRedirect([$emailTemplate])->with('success', trans('email::core.messages.templates.created', ['title' => $emailTemplate->subject]));
	}

	/**
	 * @param EmailTemplateRepository $repository
	 * @param EmailEventRepository $emailEventRepository
	 * @param integer $id
	 */
	public function getEdit(EmailTemplateRepository $repository, EmailEventRepository $emailEventRepository, $id)
	{
		WYSIWYG::loadAll();
		$emailTemplate = $this->getEmailTemplate($repository, $id);
		$this->setTitle(trans('email::core.title.templates.edit', [
			'title' => $emailTemplate->subject
		]));
		$action = 'backend.email.template.edit.post';

		$emailEvents = $emailEventRepository->eventsList();

		$this->setContent('email.template.form', compact('emailTemplate', 'action', 'emailEvents'));
	}

	/**
	 * @param EmailTemplateRepository $repository
	 * @param integer $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postEdit(EmailTemplateRepository $repository, $id)
	{
		$data = $this->request->all();

		$validator = $repository->validator($data);

		if ($validator->fails())
		{
			$this->throwValidationException($this->request, $validator);
		}

		$emailTemplate = $repository->update($id, $data);

		return $this->smartRedirect([$emailTemplate])->with('success', trans('email::core.messages.templates.updated', ['title' => $emailTemplate->subject]));
	}

	/**
	 * @param EmailTemplateRepository $repository
	 * @param integer $id
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public function postDelete(EmailTemplateRepository $repository, $id)
	{
		$emailTemplate = $this->getEmailTemplate($repository, $id);
		$emailTemplate->delete();

		return $this->smartRedirect()->with('success', trans('email::core.messages.templates.deleted', ['title' => $emailTemplate->subject]));
	}

	/**
	 * @param EmailTemplateRepository $repository
	 * @param integer $id
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	protected function getEmailTemplate(EmailTemplateRepository $repository, $id)
	{
		try
		{
			return $repository->findOrFail($id);
		}
		catch (ModelNotFoundException $e)
		{
			$this->throwFailException($this->smartRedirect()->withErrors(trans('email::core.messages.templates.not_found')));
		}

		return null;
	}

}