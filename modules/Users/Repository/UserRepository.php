<?php

namespace KodiCMS\Users\Repository;

use KodiCMS\CMS\Repository\BaseRepository;
use KodiCMS\Users\Model\User;

class UserRepository extends BaseRepository
{
    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param int|null $perPage
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null)
    {
        return $this->model->with('roles')->paginate();
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnCreate(array $data = [])
    {
        $validator = $this->validator($data, [
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'username' => 'required|max:255|min:3|unique:users',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param int $id
     * @param array   $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnUpdate($id, array $data = [])
    {
        $validator = $this->validator($data, [
            'email'    => "required|email|max:255|unique:users,email,{$id}",
            'username' => "required|max:255|min:3|unique:users,username,{$id}",
        ]);

        $validator->sometimes('password', 'required|confirmed|min:6', function ($input) {
            return ! empty($input->password);
        });

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data = [])
    {
        $user = parent::create(array_only($data, [
            'username',
            'password',
            'email',
            'locale',
        ]));

        if (isset($data['roles'])) {
            $user->roles()->attach((array) $data['roles']);
        }

        return $user;
    }

    /**
     * @param int   $id
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data = [])
    {
        if (array_key_exists('password', $data) and empty($data['password'])) {
            unset($data['password']);
        }

        $user = parent::update($id, array_only($data, [
            'username',
            'password',
            'email',
            'locale',
        ]));

        if (isset($data['roles'])) {
            $user->roles()->sync((array) $data['roles']);
        }

        return $user;
    }
}
