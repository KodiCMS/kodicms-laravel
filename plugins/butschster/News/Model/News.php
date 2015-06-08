<?php namespace Plugins\butschster\News\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Support\Model\ModelFieldTrait;
use Plugins\butschster\News\Model\FieldCollections\NewsFieldCollection;

class News extends Model
{

	use ModelFieldTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'news';

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id', 'created_at', 'updated_at', 'user_id'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'user_id' => 'integer'
	];

	/**
	 * @return array
	 */
	protected function fieldCollection()
	{
		return new NewsFieldCollection;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('\KodiCMS\Users\Model\User', 'user_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function content()
	{
		return $this->hasOne('\Plugins\butschster\News\Model\NewsContent');
	}
}