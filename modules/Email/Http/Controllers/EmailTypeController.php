<?php namespace KodiCMS\Email\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Email\Model\EmailType;
use KodiCMS\Email\Services\EmailTypeCreator;
use KodiCMS\Email\Services\EmailTypeUpdator;

class EmailTypeController extends BackendController
{

	public $moduleNamespace = 'email::';

	public function getIndex()
	{
		$emailTypes = EmailType::paginate();

		$this->setContent('email.type.list', compact('emailTypes'));
	}

	public function getCreate()
	{
		$this->setTitle(trans('email::core.title.types.create'));

		$emailType = new EmailType;
		$action = 'backend.email.type.create.post';

		$this->setContent('email.type.form', compact('emailType', 'action'));
	}

	public function postCreate(EmailTypeCreator $emailTypeCreator)
	{
		$data = $this->request->all();
		$this->formatFields($data);

		$validator = $emailTypeCreator->validator($data);

		if ($validator->fails())
		{
			$this->throwValidationException($this->request, $validator);
		}

		$emailType = $emailTypeCreator->create($data);

		return $this->smartRedirect([$emailType])->with('success', trans('email::core.messages.types.created', ['title' => $emailType->name]));
	}

	public function getEdit($id)
	{
		$emailType = $this->getEmailType($id);
		$this->setTitle(trans('email::core.title.types.edit', [
			'title' => $emailType->name
		]));
		$action = 'backend.email.type.edit.post';

		$this->setContent('email.type.form', compact('emailType', 'action'));
	}

	public function postEdit(EmailTypeUpdator $emailTypeUpdator, $id)
	{
		$data = $this->request->all();
		$this->formatFields($data);

		$validator = $emailTypeUpdator->validator($id, $data);

		if ($validator->fails())
		{
			$this->throwValidationException($this->request, $validator);
		}

		$emailType = $emailTypeUpdator->update($id, $data);

		return $this->smartRedirect([$emailType])->with('success', trans('email::core.messages.types.updated', ['title' => $emailType->name]));
	}

	public function getDelete($id)
	{
		$emailType = $this->getEmailType($id);
		$emailType->delete();

		return $this->smartRedirect()->with('success', trans('email::core.messages.types.deleted', ['title' => $emailType->name]));
	}

	protected function getEmailType($id)
	{
		try
		{
			return EmailType::findOrFail($id);
		} catch (ModelNotFoundException $e)
		{
			$this->throwFailException($this->smartRedirect()->withErrors(trans('email::core.messages.types.not_found')));
		}
		return null;
	}

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