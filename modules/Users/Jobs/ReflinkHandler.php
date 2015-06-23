<?php namespace KodiCMS\Users\Jobs;

use KodiCMS\Users\Model\User;
use Illuminate\Contracts\Bus\SelfHandling;
use KodiCMS\Users\Exceptions\ReflinkException;
use KodiCMS\Users\Repository\UserReflinkRepository;

class ReflinkHandler implements SelfHandling {

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var string
	 */
	protected $code;

	/**
	 * @param string $code
	 * @param User   $user
	 */
	public function __construct($code, User $user)
	{
		$this->code = $code;
		$this->user = $user;
	}

	/**
	 * @param UserReflinkRepository $repository
	 *
	 * @throws ReflinkException
	 */
	public function handle(UserReflinkRepository $repository)
	{
		$reflink = $repository->getModel()
			->where('code', $this->code)
			->where('user_id', $this->user->id)
			->findOrFail();

		$redirectUrl = array_get($reflink->properties, 'redirectUrl');
		$reflinkClass = $reflink->type;

		if (empty($reflinkClass))
		{
			throw new ReflinkException("Reflink [{$this->code}] hasn't type");
		}
		else if (!class_exists($reflinkClass))
		{
			throw new ReflinkException("Class [{$reflinkClass}] is not found");
		}

		if ((new $reflinkClass())->handle($reflink))
		{
			$reflink->delete();
		}

		if (filter_input($redirectUrl, FILTER_VALIDATE_URL))
		{
			redirect($redirectUrl);
		}
	}
}