<?php namespace KodiCMS\Users\Model;

use KodiCMS\Users\Helpers\Gravatar;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravelrus\LocalizedCarbon\LocalizedCarbon as Carbon;

/**
 * Class User
 * @package KodiCMS\Users\Model
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id', 'last_login', 'logins'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'logins' => 'integer',
		'last_login' => 'integer'
	];

	/**
	 * @var array
	 */
	protected $roles = [];

	/**
	 * @var array
	 */
	protected $permissions = [];

	/**
	 * @param integer $date
	 * @return string
	 */
	public function getLastLoginAttribute($date)
	{
		return (new Carbon)->createFromTimestamp($date)->diffForHumans();
	}

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

	/**
	 * TODO: добавить кеширование ролей
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany('KodiCMS\Users\Model\UserRole', 'roles_users', 'user_id', 'role_id');
	}

	public function hasRole($role, $allRequired = FALSE)
	{
		$status = TRUE;

		$roles = $this->roles()->lists('name');

		if (is_array($role))
		{
			$status = (bool) $allRequired;

			foreach ($role as $_role) {
				// If the user doesn't have the role
				if (!in_array($_role, $roles)) {
					// Set the status false and get outta here
					$status = FALSE;

					if ($allRequired) {
						break;
					}
				} elseif (!$allRequired) {
					$status = TRUE;
					break;
				}
			}
		}
		else
		{
			$status = in_array($role, $roles);
		}

		return $status;
	}

	/**
	 * @return array
	 */
	public function getPermissionsByRoles()
	{
		$roles = $this->roles()
			->get()
			->lists('name', 'id');

		if(!empty($roles)) {
			$permissions = (new RolePermission())
				->whereIn('role_id', array_keys($roles))
				->get()
				->lists('action');
		}

		return array_unique($permissions);
	}

	/**
	 * @return array
	 */
	public function getAllowedPermissions()
	{
		$permissions = [];

		foreach (\ACL::getPermissions() as $sectionTitle => $actions) {
			foreach ($actions as $action => $title) {
				if (acl_check($action, $this)) {
					$permissions[$sectionTitle][$action] = $title;
				}
			}
		}

		return $permissions;
	}

	/**
	 * @return string
	 */
	public function getLocaleAttribute()
	{
		if(!empty($this->attributes['locale'])) {
			return $this->attributes['locale'];
		}

		return config('app.locale');
	}
}
