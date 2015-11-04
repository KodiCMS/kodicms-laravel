<?php

namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KodiCMS\CMS\Loader\ModuleLoader
 */
class Reflinks extends Facade
{
    /**
     * @var string
     */
    const INVALID_TOKEN = 'users::reflinks.messages.invalid_token';

    /**
     * @var string
     */
    const TOKEN_NOT_GENERATED = 'users::reflinks.messages.token_not_generated';

    /**
     * @var string
     */
    const TOKEN_GENERATED = 'users::reflinks.messages.token_generated';

    /**
     * @var string
     */
    const TOKEN_HANDLED = 'users::reflinks.messages.token_handled';

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'reflinks';
    }
}
