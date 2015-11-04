<?php

namespace KodiCMS\Email\Repository;

use KodiCMS\Email\Model\EmailTemplate;
use KodiCMS\CMS\Repository\BaseRepository;

class EmailTemplateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $validationRules = [
        'status'         => 'required|boolean',
        'use_queue'      => 'required|boolean',
        'email_from'     => 'required',
        'email_to'       => 'required',
        'subject'        => 'required',
        'message'        => 'required',
        'email_event_id' => 'required|exists:email_events,id',
    ];

    /**
     * @param EmailTemplate $model
     */
    public function __construct(EmailTemplate $model)
    {
        parent::__construct($model);
    }
}
