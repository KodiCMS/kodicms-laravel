<?php namespace KodiCMS\Email\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Email\Model\EmailType;

class EmailTypeController extends Controller
{
	/**
	 * @var bool
	 */
	public $authRequired = TRUE;

	public function getOptions()
	{
		$uid = $this->request->get('uid');

		$emailType = EmailType::findOrFail($uid);
		$options = array_merge($emailType->fields, config('email.default_template_data'));

		$this->setContent($options);
	}
}