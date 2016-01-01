<?php

namespace KodiCMS\Email\Jobs;

use KodiCMS\Email\Model\EmailEvent;
use KodiCMS\Email\Exceptions\EmailEventException;

class EmailSend
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param string $code
     * @param array  $options
     */
    public function __construct($code, $options = [])
    {
        $this->code = $code;
        $this->options = $options;
    }

    public function handle()
    {
        $emailEvent = EmailEvent::whereCode($this->code)->first();

        if (is_null($emailEvent)) {
            throw new EmailEventException(
                trans('email::core.messages.events.job_not_found', ['name' => $this->code])
            );
        }

        $emailEvent->send($this->options);
    }
}
