<?php namespace KodiCMS\Users\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Users\Exceptions\ReflinkException;

class UserReflink extends Model
{
	public static function cleanOld()
	{
		$model = new static();

		$model
			->whereRaw('created_ad < CURDATE() - INTERVAL 1 DAY')
			->delete();
	}

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_reflinks';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['type', 'code', 'properties'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'user_id' => 'integer',
		'type' => 'string',
		'code' => 'string',
		'properties' => 'array'
	];

	/**
	 * @return string
	 */
	public function link()
	{
		return route('reflink', ['code' => $this->code]);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\belongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * @param User   $user
	 * @param string $type
	 * @param array  $properties
	 *
	 * @return static
	 * @throws ReflinkException
	 */
	public function generate(User $user, $type, array $properties = [])
	{
		if (!$user->exists)
		{
			throw new ReflinkException("User not loaded");
		}

		$refLink = $user
			->reflinks()
			->where('type', $type)
			->whereRaw('created_at > CURDATE() - INTERVAL 1 HOUR')
			->get()
			->first();

		if (is_null($refLink))
		{
			$refLink = static::create([
				'code' => uniqid(TRUE) . sha1(microtime()),
				'type' => $type,
				'properties' => $properties
			]);

			$refLink->user()->associate($user)->save();
		}
		else
		{
			$refLink->update([
				'properties' => $properties
			]);
		}

		return $refLink;
	}
}