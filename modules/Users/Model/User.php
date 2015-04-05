<?php namespace KodiCMS\Users\Model;

use KodiCMS\Users\Helpers\Gravatar;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, CashableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * Получение аватара пользлователя из сервиса Gravatar
	 *
	 * @param integer $size
	 * @param string $default
	 * @param array $attributes
	 * @return string HTML::image
	 */
	public function gravatar($size = 100, $default = NULL, array $attributes = NULL)
	{
		return Gravatar::load($this->email, $size, $default, $attributes);
	}
	
	/**
	 * 
	 * @param string $password
	 */
	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = \Hash::make($password);
	}

	public function toggleFollow(User $user)
	{
		if($this->followers()
				->newPivotStatement()
				->where('user_id', $this->id)
				->where('follower_id', $user->id)
				->first() !== NULL)
		{
			$this->followers()->detach($user);
			$this->decrement('count_followers');
			return NULL;
		}
		else
		{
			$this->followers()->attach($user);
			$this->increment('count_followers');
			return TRUE;
		}
	}


	/**
	 * 
	 * @return HasMany
	 */
	public function articles()
	{
		return $this->hasMany('App\Models\Article', 'author_id')
			->OrderByDate()
			->withFavorite()
			->with('categories', 'author');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function favorites()
	{
		return $this->belongsToMany('App\Models\Article', 'user_favorites')
			->OrderByDate()
			->withFavorite()
			->published()
			->with('categories', 'author');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function followers()
	{
		return $this->belongsToMany('App\Models\User', 'user_followers', 'user_id', 'follower_id');
	}
}
