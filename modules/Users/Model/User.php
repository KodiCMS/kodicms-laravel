<?php namespace KodiCMS\Users\Model;

use App;
use ACL;
use Carbon\Carbon;
use KodiCMS\Support\Helpers\Locale;
use KodiCMS\Users\Helpers\Gravatar;
use Illuminate\Auth\Authenticatable;
use KodiCMS\Support\Traits\Tentacle;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Support\Model\ModelFieldTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use KodiCMS\Users\Model\FieldCollections\UserFieldCollection;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * Class User
 * @package KodiCMS\Users\Model
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract {

	use Authenticatable, CanResetPassword, ModelFieldTrait, Authorizable, Tentacle;

	/**
	 * @var array
	 */
	private static $loadedUserRoles = [];

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
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		$this->addObservableEvents('authenticated');
	}

	/**
	 * @return array
	 */
	protected function fieldCollection()
	{
		return new UserFieldCollection;
	}

	/**
	 * @param integer $date
	 * @return string
	 */
	public function getLastLoginAttribute($date)
	{
		return (new Carbon())->createFromTimestamp($date)->diffForHumans();
	}

	/**
	 * @return string
	 */
	public function getCurrentTheme()
	{
		return UserMeta::get('cms_theme', config('cms.theme.default'), $this->id);
	}

	/**
	 * @return array
	 */
	public function getAvailableLocales()
	{
		$locales = Locale::getAvailable();
		$systemDefault = Locale::getSystemDefault();

		$locales[Locale::DEFAULT_LOCALE] = trans('users::core.field.default_locale', [
			'locale' => array_get($locales, $systemDefault, $systemDefault)
		]);

		return $locales;
	}

	/**
	 * @param int $size
	 * @param array $attributes
	 * @return string
	 */
	public function getAvatar($size = 100, array $attributes = null)
	{
		if (empty($this->avatar) or !is_file(App::uploadPath() . 'avatars' . DIRECTORY_SEPARATOR . $this->avatar))
		{
			return $this->getGravatar($size, null, $attributes);
		}

		return HTML::image(App::uploadURL() . '/avatars/' . $this->avatar, null, $attributes);
	}

	/**
	 * Получение аватара пользлователя из сервиса Gravatar
	 *
	 * @param integer $size
	 * @param string $default
	 * @param array $attributes
	 * @return string HTML::image
	 */
	public function getGravatar($size = 100, $default = null, array $attributes = null)
	{
		return Gravatar::load($this->email, $size, $default, $attributes);
	}
	
	/**
	 * @param string $password
	 */
	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = bcrypt($password);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function roles()
	{
		return $this->belongsToMany(UserRole::class, 'roles_users', 'user_id', 'role_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\hasMany
	 */
	public function reflinks()
	{
		return $this->hasMany(UserReflink::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getRoles()
	{
		if (array_key_exists($this->id, static::$loadedUserRoles))
		{
			return static::$loadedUserRoles[$this->id];
		}

		$roles = $this->roles()->get();
		static::$loadedUserRoles[$this->id] = $roles;

		return $roles;
	}

	/**
	 * @param string|array $role
	 * @param bool         $allRequired
	 *
	 * @return bool
	 */
	public function hasRole($role, $allRequired = FALSE)
	{
		$roles = $this->getRoles()->lists('name')->all();

		if (is_array($role))
		{
			$status = (bool) $allRequired;

			foreach ($role as $_role) {
				// If the user doesn't have the role
				if (!in_array($_role, $roles)) {
					// Set the status false and get outta here
					$status = false;

					if ($allRequired) {
						break;
					}
				} elseif (!$allRequired) {
					$status = true;
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
		$roles = $this->getRoles()
			->lists('name', 'id')
			->all();

		if(!empty($roles)) {
			$permissions = (new RolePermission())
				->whereIn('role_id', array_keys($roles))
				->get()
				->lists('action')
				->all();

			return array_unique($permissions);
		}

		return [];
	}

	/**
	 * @return array
	 */
	public function getAllowedPermissions()
	{
		$permissions = [];

		foreach (ACL::getPermissionsList() as $sectionTitle => $actions) {
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
	public function getLocale()
	{
		if (!empty($this->attributes['locale']))
		{
			$locale = $this->attributes['locale'];

			if ($locale != Locale::DEFAULT_LOCALE)
			{
				return $locale;
			}
		}

		return Locale::getSystemDefault();
	}


	public function authenticated()
	{
		$this->fireModelEvent('authenticated');
	}
}
