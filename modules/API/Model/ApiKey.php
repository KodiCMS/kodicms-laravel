<?php namespace KodiCMS\API\Model;

use Illuminate\Database\Eloquent\Model;
use Keys;

class ApiKey extends Model {

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = FALSE;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'api_keys';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['id', 'description'];

	/**
	 * @param string $description
	 * @return integer|null
	 */
	public static function generateKey($description = '')
	{
		$key = static::create([
			'id' => Keys::generate(),
			'description' => $description
		]);

		return $key->id;
	}

	/**
	 * @param $oldKey
	 * @return bool|integer
	 */
	public static function refresh($oldKey)
	{
		$key = static::where('id', $oldKey)->first();

		if (!$key->exists)
		{
			return false;
		}

		$key->update([
			'id' => static::generateKey()
		]);

		return $key->id;
	}

	/**
	 *
	 * @param string $key
	 * @return bool
	 */
	public function isValid($key)
	{
		return $this
			->where('id', e($key))
			->first()
			->exists;
	}
}