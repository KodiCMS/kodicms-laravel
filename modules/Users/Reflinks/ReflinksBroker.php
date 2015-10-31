<?php
namespace KodiCMS\Users\Reflinks;

use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\Users\Exceptions\ReflinkException;
use KodiCMS\Users\Contracts\ReflinkGeneratorInterface;

class ReflinksBroker
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
     * @var ReflinkTokenRepository $tokens
     */
    protected $tokens;


    /**
     * @param ReflinkTokenRepository $tokens
     */
    public function __construct(ReflinkTokenRepository $tokens)
    {
        $this->tokens = $tokens;
    }


    /**
     * @param ReflinkGeneratorInterface $generator
     *
     * @return string
     */
    public function generateToken(ReflinkGeneratorInterface $generator)
    {
        try {
            if (is_null($user = $generator->getUser())) {
                throw new ReflinkException(trans('users::reflinks.messages.user_not_found'));
            }

            if ($token = $this->tokens->create($user, $generator->getHandlerClass(), $generator->getProperties())) {
                $generator->tokenGenerated($token);

                return static::TOKEN_GENERATED;
            }

            return static::TOKEN_NOT_GENERATED;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * @param string $token
     *
     * @return string
     * @throws ReflinkException
     * @throws \Exception
     */
    public function handle($token)
    {
        if ( ! $this->tokens->exists($token)) {
            return static::INVALID_TOKEN;
        }

        $reflink             = $this->tokens->load($token);
        $reflinkHandlerClass = $reflink->handler;

        if (empty( $reflinkHandlerClass )) {
            throw new ReflinkException("Reflink token [{$token}] hasn't handler");
        } else if ( ! class_exists($reflinkHandlerClass)) {
            throw new ReflinkException("Class [{$reflinkHandlerClass}] is not found");
        }

        try {
            $handler = app()->make($reflinkHandlerClass, [$reflink]);

            app()->call([$handler, 'handle']);
            $reflink->delete();

            return $handler;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}