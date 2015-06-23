<?php namespace KodiCMS\Users\Jobs;

use KodiCMS\Users\Model\User;
use Illuminate\Contracts\Bus\SelfHandling;
use KodiCMS\Users\Contracts\ReflinkInterface;
use KodiCMS\Users\Exceptions\ReflinkException;
use KodiCMS\Users\Repository\UserReflinkRepository;

class ReflinkGenerator implements SelfHandling {

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var ReflinkInterface
	 */
	protected $type;

	/**
	 * @var array
	 */
	protected $properties = [];

	/**
	 * @param string $type
	 * @param User   $user
	 * @param array  $properties
	 *
	 * @throws ReflinkException
	 */
	public function __construct($type, User $user, array $properties = [])
	{
		if (!class_exists($type))
		{
			throw new ReflinkException("Class [{$type}] not found");
		}

		$class = new $type;

		if (!($class instanceof ReflinkInterface))
		{
			throw new ReflinkException("Class [{$type}] must be instance of [KodiCMS\Users\Contracts\ReflinkInterface]");
		}

		$this->type = $class;
		$this->user = $user;
		$this->properties = $properties;
	}

	/**
	 * @param UserReflinkRepository $repository
	 *
	 * @return bool
	 */
	public function handle(UserReflinkRepository $repository)
	{
		if (
			$reflink = $repository->getModel()->generate($this->user, get_class($this->type), $this->properties)
			and
			method_exists($this->type, 'generate')
		)
		{
			$this->type->generate($reflink);

			return $reflink;
		}

		return false;
	}
}