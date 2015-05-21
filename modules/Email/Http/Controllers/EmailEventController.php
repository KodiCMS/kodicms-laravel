<?php namespace KodiCMS\Email\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Email\Model\EmailType;
use KodiCMS\Email\Repository\EmailEventRepository;
use KodiCMS\Email\Services\EmailTypeCreator;
use KodiCMS\Email\Services\EmailTypeUpdator;

class EmailEventController extends BackendController
{

	public $moduleNamespace = 'email::';

	public function getIndex(EmailEventRepository $repository)
	{
		$emailEvents = $repository->paginate();

		$this->setContent('email.event.list', compact('emailEvents'));
	}

	public function getCreate(EmailEventRepository $repository)
	{
		$this->setTitle(trans('email::core.title.events.create'));

		$emailEvent = $repository->instance();
		$action = 'backend.email.event.create.post';

		$this->setContent('email.event.form', compact('emailEvent', 'action'));
	}

	public function postCreate(EmailEventRepository $repository)
	{
		$data = $this->request->all();
		$this->formatFields($data);

		$validator = $repository->validatorOnCreate($data);

		if ($validator->fails())
		{
			$this->throwValidationException($this->request, $validator);
		}

		$emailEvent = $repository->create($data);

		return $this->smartRedirect([$emailEvent])->with('success', trans('email::core.messages.events.created', ['title' => $emailEvent->name]));
	}

	public function getEdit(EmailEventRepository $repository, $id)
	{
		$emailEvent = $this->getEmailType($repository, $id);
		$this->setTitle(trans('email::core.title.events.edit', [
			'title' => $emailEvent->name
		]));
		$action = 'backend.email.event.edit.post';

		$this->setContent('email.event.form', compact('emailEvent', 'action'));
	}

	public function postEdit(EmailEventRepository $repository, $id)
	{
		$data = $this->request->all();
		$this->formatFields($data);

		$validator = $repository->validatorOnUpdate($data);

		if ($validator->fails())
		{
			$this->throwValidationException($this->request, $validator);
		}

		$emailEvent = $repository->update($id, $data);

		return $this->smartRedirect([$emailEvent])->with('success', trans('email::core.messages.events.updated', ['title' => $emailEvent->name]));
	}

	public function getDelete(EmailEventRepository $repository, $id)
	{
		$emailEvent = $this->getEmailType($repository, $id);
		$emailEvent->delete();

		return $this->smartRedirect()->with('success', trans('email::core.messages.events.deleted', ['title' => $emailEvent->name]));
	}

	protected function getEmailType(EmailEventRepository $repository, $id)
	{
		try
		{
			return $repository->findOrFail($id);
		} catch (ModelNotFoundException $e)
		{
			$this->throwFailException($this->smartRedirect()->withErrors(trans('email::core.messages.events.not_found')));
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