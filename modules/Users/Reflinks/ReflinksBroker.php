<?php namespace KodiCMS\Users\Reflinks;

use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\Users\Contracts\ReflinkInterface;
use KodiCMS\Users\Model\User;

class ReflinksBroker {

	/**
	 * @var string
	 */
	const INVALID_TOKEN = 'reflinks::messages.invalid_token';

	/**
	 * @var string
	 */
	const TOKEN_NOT_GENERATED = 'reflinks::messages.token_not_generated';

	/**
	 * @var string
	 */
	const TOKEN_GENERATED = 'reflinks::messages.token_generated';

	/**
	 * @var string
	 */
	const TOKEN_HANDLED = 'reflinks::messages.token_handled';

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
	 * @param User $user
	 * @param ReflinkInterface $type
	 * @param array $properties
	 * @return string
	 */
	public function generateToken(User $user, ReflinkInterface $type, array $properties = [])
	{
		try
		{
			if($token = $this->tokens->create($user, $type, $properties))
			{
				if (method_exists($type, 'generate'))
				{
					$type->generate($token);
				}

				return static::TOKEN_GENERATED;
			}

			return static::TOKEN_NOT_GENERATED;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}

	}

	public function handle($token)
	{
		if (!$this->tokens->exists($token))
		{
			return static::INVALID_TOKEN;
		}

		$reflink = $this->tokens->load($token);
		$reflinkClass = $reflink->type;

		if (empty($reflinkClass))
		{
			throw new ReflinkException("Reflink token [{$token}] hasn't type");
		}
		else if (!class_exists($reflinkClass))
		{
			throw new ReflinkException("Class [{$reflinkClass}] is not found");
		}

		try
		{
			if ((new $reflinkClass())->handle($reflink))
			{
				$reflink->delete();
			}

			$redirectUrl = array_get($reflink->properties, 'redirectUrl');

			if (filter_input($redirectUrl, FILTER_VALIDATE_URL))
			{
				return $redirectUrl;
			}

			return static::TOKEN_HANDLED;
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
	}
}