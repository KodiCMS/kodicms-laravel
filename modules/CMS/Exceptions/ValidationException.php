<?php

namespace KodiCMS\CMS\Exceptions;

use Illuminate\Validation\Validator;

class ValidationException extends Exception
{
    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Validator $validator
     *
     * @return $this
     */
    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
        $this->messages = $validator->errors()->getMessages();
        $this->rules = $validator->failed();

        return $this;
    }

    /**
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @return array
     */
    public function getFailedRules()
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->messages;
    }
}
