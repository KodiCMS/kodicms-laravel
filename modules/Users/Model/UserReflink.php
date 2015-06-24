<?php namespace KodiCMS\Users\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Users\Exceptions\ReflinkException;

class UserReflink extends Model
{
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
	public function linkCode()
	{
		return route('reflink.code', ['code' => $this->code]);
	}

	/**
	 * @return string
	 */
	public function link()
	{
		return route('reflink.form');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\belongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}