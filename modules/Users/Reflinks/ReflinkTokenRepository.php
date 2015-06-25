<?php namespace KodiCMS\Users\Reflinks;

use KodiCMS\Users\Model\User;
use KodiCMS\Users\Model\UserReflink;

class ReflinkTokenRepository
{
	/**
	 * The hashing key.
	 *
	 * @var string
	 */
	protected $hashKey;

	/**
	 * The number of seconds a token should last.
	 *
	 * @var int
	 */
	protected $expires;

	/**
	 * @var UserReflink
	 */
	protected $model;

	/**
	 * @param string $hashKey
	 * @param int $expires
	 */
	public function __construct($hashKey, $expires = 60)
	{
		$this->model = new UserReflink;

		$this->hashKey = $hashKey;
		$this->expires = $expires * 60;
	}

	/**
	 * @param User $user
	 * @param string $handler
	 * @param array $properties
	 * @return string
	 */
	public function create(User $user, $handler, array $properties = [])
	{
		$this->deleteExisting($user, $handler);

		// We will create a new, random token for the user so that we can e-mail them
		// a safe link to the password reset form. Then we will insert a record in
		// the database so that we can verify the token within the actual reset.
		$token = $this->createNewToken();



		return$this->getModel()->create($this->getPayload($user->id, $handler, $properties, $token));
	}

	/**
	 * @param string $token
	 * @return UserReflink
	 */
	public function load($token)
	{
		return $this->getModel()
			->where('token', $token)
			->first();
	}

	/**
	 * @param string $token
	 * @return bool
	 */
	public function exists($token)
	{
		$token = $this->load($token);
		return $token && !$this->tokenExpired($token->toArray());
	}

	/**
	 * Delete a token record by token.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function delete($token)
	{
		$this->getTable()
			->where('token', $token)
			->delete();
	}

	/**
	 * Create a new token for the user.
	 *
	 * @return string
	 */
	public function createNewToken()
	{
		return hash_hmac('sha256', str_random(40), $this->hashKey);
	}

	/**
	 * Delete expired tokens.
	 *
	 * @return void
	 */
	public function deleteExpired()
	{
		$expiredAt = Carbon::now()->subSeconds($this->expires);

		$this->getModel()
			->where('created_at', '<', $expiredAt)
			->delete();
	}

	/**
	 * @param User $user
	 * @param string $handler
	 * @return int
	 */
	protected function deleteExisting(User $user, $handler)
	{
		return $this->getModel()
			->where('user_id', $user->id)
			->where('handler', $handler)
			->delete();
	}

	/**
	 * @param integer $userId
	 * @param string $handler
	 * @param array $properties
	 * @param string $token
	 * @return array
	 */
	protected function getPayload($userId, $handler, array $properties, $token)
	{
		return [
			'user_id' => $userId,
			'token' => $token,
			'handler' => $handler,
			'properties' => $properties
		];
	}

	/**
	 * Determine if the token has expired.
	 *
	 * @param  array  $token
	 * @return bool
	 */
	protected function tokenExpired($token)
	{
		$expirationTime = strtotime($token['created_at']) + $this->expires;

		return $expirationTime < $this->getCurrentTime();
	}

	/**
	 * Get the current UNIX timestamp.
	 *
	 * @return int
	 */
	protected function getCurrentTime()
	{
		return time();
	}

	/**
	 * Begin a new database query against the table.
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function getModel()
	{
		return $this->model;
	}
}