<?php

namespace KodiCMS\CMS\Contracts;

interface ModelUpdator
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param int $id
     * @param  array  $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator($id, array $data);

    /**
     * Create a new user instance after a valid registration.
     *
     * @param int $id
     * @param  array  $data
     *
     * @return Model
     */
    public function update($id, array $data);
}
