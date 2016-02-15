<?php

namespace KodiCMS\CMS\Http\Middleware;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{


    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @return void
     */
    public function __construct(Application $app, Encrypter $encrypter)
    {
        // TODO: добавить возможность, чтобы модули сами могли добавлять исключения
        $this->except[] = 'api.filemanager';

        parent::__construct($app, $encrypter);
    }
}
