<?php namespace KodiCMS\Users\Http\Controllers;

use KodiCMS\CMS\Helpers\WYSIWYG;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Users\Model\Messages;
use KodiCMS\Users\Model\User;

class MessageController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'users::';

	/**
	 * @var array
	 */
	protected $allowedActions = ['getIndex', 'getAdd', 'getView'];

	public function getIndex()
	{
		$messages = Messages::getByUserId($this->currentUser->id)->get();
		$this->setContent('messages.list', compact('messages'));
	}

	public function getCreate()
	{
		WYSIWYG::loadAll(WYSIWYG::TYPE_HTML);

		$to = $this->request->get('to');
		$toId = User::find($to);

		$this->setTitle(trans('users::message.title.create'));

		$this->setContent('messages.add', [
			'to' => !is_null($toId) ? $toId->id : NULL,
			'userId' => $this->currentUser->id
		]);
	}

	public function getRead($id)
	{
		$message = Messages::getById($id, $this->currentUser->id);

		$this->templateScripts['MESSAGE'] = $message;
		$this->setContent('messages.view', compact('message'));
	}
}