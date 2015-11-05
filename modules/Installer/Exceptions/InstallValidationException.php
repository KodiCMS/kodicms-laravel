<?php

namespace KodiCMS\Installer\Exceptions;

use Illuminate\Validation\Validator;
use KodiCMS\CMS\Exceptions\Exception;

class InstallValidationException extends Exception
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Validator $object
     */
    public function setValidator(Validator $object)
    {
        $this->validator = $object;
    }

    public function getValidator()
    {
        return $this->validator;
    }
}
