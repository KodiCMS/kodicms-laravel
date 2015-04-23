<?php namespace KodiCMS\Users\Model;

use Illuminate\Database\Eloquent\Model;

class MessageUsers extends Model
{
	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'messages_users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['status', 'user_id', 'message_id', 'parent_id'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function message() {
		return $this->belongsTo('KodiCMS\Users\Model\Messages');
	}
}