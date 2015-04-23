<?php namespace KodiCMS\Users\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use DB;

class Messages extends Model
{
	const STATUS_READ	= 1; // Сообщение прочитано
	const STATUS_NEW	= 0; // Новое сообщение

	const STARRED		= 1;
	const NOT_STARRED	= 0;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'messages';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title', 'message', 'from_user_id'];

	/**
	 * @param Builder $query
	 * @param int $userId
	 * @param int $parentId
	 */
	public function scopeGetByUserId($query, $userId, $parentId = 0)
	{
		$minStatus = DB::table('messages_users as ms')
			->select(DB::raw('MIN(ms.status)'))
			->where('ms.parent_id', DB::raw('messages_users.message_id'))
			->orWhere('ms.message_id', DB::raw('messages_users.message_id'))
			->toSql();

		$query
			->select($this->getTable() . '.*', 'users.username as author', 'messages_users.status', DB::raw("({$minStatus}) as is_read"))
			->leftJoin('messages_users', $this->getTable() . '.id', '=', 'messages_users.message_id')
			->leftJoin('users', $this->getTable() . '.from_user_id', '=', 'users.id')
			->where('messages_users.user_id', (int) $userId)
			->where('messages_users.parent_id', (int) $parentId)
			->orderBy($this->getTable() . '.created_at', 'desc')
			->get();
	}

	/**
	 * @param int $messageId
	 * @param int $userId
	 */
	public function getById($messageId, $userId)
	{
		$query = DB::table('messages_users')
			->select('from_user_id', 'messages.id', 'users.username as author', 'messages_users.status as is_read')
			->leftJoin('users', 'from_user_id', '=', 'users.id')
			->leftJoin('messages', 'messages.id', '=', 'messages_users.message_id')
			->where('messages_users.user_id', (int) $userId)
			->where('messages.id', (int) $messageId)
			->first();
	}

	/**
	 * @param string $title
	 * @param string $text
	 * @param array $to
	 * @param int $parentId
	 * @param integer|null $from
	 * @param bool $sendToAuthor
	 * @return bool|int
	 */
	public function sendMessage($title, $text, array $to, $parentId = 0, $from = null, $sendToAuthor = true)
	{
		if(empty($from))
		{
			$from = null;
		}

		if(empty($to))
		{
			return false;
		}

		if(!is_null($from) AND $sendToAuthor) $to[] = $from;

		$to = array_unique($to);

		$message = $this->create([
			'from_user_id' => $from,
			'message' => $text,
			'title' => $title
		]);

		if(!$message->exists) return false;

		foreach($to as $id)
		{
			MessageUsers::create([
				'status' => static::STATUS_NEW,
				'user_id' => (int) $id,
				'message_id' => $message->id,
				'parent_id' => (int) $parentId
			]);
		}

		return $message->id;
	}

	/**
	 * @param integer $messageId
	 * @param integer $userId
	 */
	public function markRead($messageId, $userId)
	{
		MessageUsers::where('user_id', (int) $userId)
			->where('message_id', (int) $messageId)
			->update([
				'status' => static::STATUS_READ,
			]);
	}

	/**
	 * @return int
	 */
	public function countNew()
	{
		return MessageUsers::select('COUNT(*) as total')
			->where('status', static::STATUS_NEW)
			->where('user_id', (int) $userId)
			->pluck('total');
	}

	/**
	 * @param int $userId
	 * @param int $messageId
	 * @return bool
	 */
	public function deleteByUserId($userId, $messageId)
	{
		MessageUsers::where('user_id', (int) $userId)
			->where(function($query) {
				$query->where('message_id', (int) $messageId)
					->or_where('parent_id', (int) $messageId);
			})
			->delete();

		$count = (int) MessageUsers::select('COUNT(*) as total')
			->where('message_id', (int) $messageId)
			->pluck('total');

		if ($count == 0)
		{
			$this->deleteById($messageId);
		}

		return TRUE;
	}

	/**
	 * @param int $messageId
	 * @return bool
	 */
	public function deleteById($messageId)
	{
		(new static)->where('id', (int) $messageId)->delete();

		return TRUE;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function users() {
		return $this->hasMany('\KodiCMS\Users\Model\MessageUsers');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function author()
	{
		return $this->belongsTo('\KodiCMS\Users\Model\User', 'from_user_id');
	}
}