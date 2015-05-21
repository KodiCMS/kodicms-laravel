<?php namespace KodiCMS\Email\Repository;

use KodiCMS\CMS\Repository\BaseRepository;
use KodiCMS\Email\Model\EmailTemplate;

class EmailTemplateRepository extends BaseRepository
{

	protected $validationRules = [
		'status' => 'required|boolean',
		'use_queue' => 'required|boolean',
		'email_from' => 'required',
		'email_to' => 'required',
		'subject' => 'required',
		'message' => 'required',
		'email_event_id' => 'required|exists:email_events,id',
	];

	function __construct(EmailTemplate $model)
	{
		parent::__construct($model);
	}

}