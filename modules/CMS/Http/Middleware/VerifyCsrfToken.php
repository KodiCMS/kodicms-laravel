<?php

namespace KodiCMS\CMS\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @return void
     */
    public function __construct(Encrypter $encrypter)
    {
        $this->encrypter = $encrypter;

        // TODO: добавить возможность, чтобы модули сами могли добавлять исключения
        $this->except[] = 'api.filemanager';
    }
}
