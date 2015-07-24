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
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['published_at'];

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
		return $this->belongsTo(\KodiCMS\Users\Model\User::class, 'user_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function content()
	{
		return $this->hasOne(\Plugins\butschster\News\Model\NewsContent::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function comment()
	{
		return $this->hasMany(\Plugins\butschster\News\Model\NewsComment::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function category()
	{
		return $this->belongsTo(\Plugins\butschster\News\Model\NewsCategory::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function tags()
	{
		return $this->belongsToMany(\Plugins\butschster\News\Model\NewsTag::class, 'news_have_tags', 'news_id', 'tag_id');
	}

	/*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
	public function scopeForAuthor($query)
	{
		return $query->whereUserId(Auth::user()->id);
	}

	public function scopeBySlug($query, $slug)
	{
		return $query->whereSlug($slug)->first();
	}
}