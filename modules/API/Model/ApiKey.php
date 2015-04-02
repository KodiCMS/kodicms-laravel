<?php namespace KodiCMS\API\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\API\Helpers\Keys;

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
	public function geterateKey($description = '')
	{
		$key = $this->create([
			'id' => Keys::generate(),
			'description' => $description
		]);

		return $key->id;
	}

	/**
	 * @param $oldKey
	 * @return bool|integer
	 */
	public function refresh($oldKey)
	{
		$this->where('id', $oldKey)->first();

		if (!$this->exists)
		{
			return FALSE;
		}

		$key = $this->update([
			'id' => self::generate_key()
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