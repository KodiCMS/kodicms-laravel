<?php namespace KodiCMS\Email\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use WYSIWYG;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Email\Model\EmailTemplate;
use KodiCMS\Email\Model\EmailType;
use KodiCMS\Email\Services\EmailTemplateCreator;
use KodiCMS\Email\Services\EmailTemplateUpdator;

class EmailTemplateController extends BackendController
{

	public $moduleNamespace = 'email::';

	public function getIndex()
	{
		$emailTemplates = EmailTemplate::paginate();

		$this->setContent('email.template.list', compact('emailTemplates'));
	}

	public function getCreate()
	{
		WYSIWYG::loadAll();
		$this->setTitle(trans('email::core.title.templates.create'));

		$emailTemplate = new EmailTemplate;

		$emailTemplate->message_type = EmailTemplate::TYPE_HTML;
		$emailTemplate->subject = '{site_title}';
		$emailTemplate->email_from = '{default_email}';
		$emailTemplate->email_to = '{email_to}';
		$emailTemplate->email_type_id = $this->request->get('email_type_id');

		$action = 'backend.email.template.create.post';

		$emailTypes = EmailType::all()->lists('fullName', 'id');

		$this->setContent('email.template.form', compact('emailTemplate', 'action', 'emailTypes'));
	}

	public function postCreate(EmailTemplateCreator $emailTemplateCreator)
	{
		$data = $this->request->all();

		$validator = $emailTemplateCreator->validator($data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$emailTemplate = $emailTemplateCreator->create($data);

		return $this->smartRedirect([$emailTemplate])
			->with('success', trans('email::core.messages.templates.created', ['title' => $emailTemplate->subject]));
	}

	public function getEdit($id)
	{
		WYSIWYG::loadAll();
		$emailTemplate = $this->getEmailTemplate($id);
		$this->setTitle(trans('email::core.title.templates.edit', [
			'title' => $emailTemplate->subject
		]));
		$action = 'backend.email.template.edit.post';

		$emailTypes = EmailType::all()->lists('fullName', 'id');

		$this->setContent('email.template.form', compact('emailTemplate', 'action', 'emailTypes'));
	}

	public function postEdit(EmailTemplateUpdator $emailTemplateUpdator, $id)
	{
		$data = $this->request->all();

		$validator = $emailTemplateUpdator->validator($id, $data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$emailTemplate = $emailTemplateUpdator->update($id, $data);

		return $this->smartRedirect([$emailTemplate])
			->with('success', trans('email::core.messages.templates.updated', ['title' => $emailTemplate->subject]));
	}

	public function getDelete($id)
	{
		$emailTemplate = $this->getEmailTemplate($id);
		$emailTemplate->delete();

		return $this->smartRedirect()
			->with('success', trans('email::core.messages.templates.deleted', ['title' => $emailTemplate->subject]));
	}

	protected function getEmailTemplate($id)
	{
		try {
			return EmailTemplate::findOrFail($id);
		}
		catch (ModelNotFoundException $e) {
			$this->throwFailException($this->smartRedirect()->withErrors(trans('email::core.messages.templates.not_found')));
		}
		return null;
	}

}