<?php namespace KodiCMS\Users\Model;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = FALSE;


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'description'];

	/**
	 * Получение прав для роли
	 * @return array
	 */
	public function permissions()
	{
		return $this->hasMany('KodiCMS\Users\Model\RolePermission');
	}

	public function attachPermissions(array $permissionsList = [])
	{
		$this->permissions()->delete();

		if (count($permissionsList) > 0) {
			$permissions = [];

			foreach (array_keys($permissionsList) as $action) {
				$permissions[] = new RolePermission(['action' => $action]);
			}

			$this->permissions()->saveMany($permissions);
		}

		return $this;
	}

	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function users()
	{
		return $this->belongsToMany('KodiCMS\Users\Model\User', 'users_roles', 'user_id');
	}
}