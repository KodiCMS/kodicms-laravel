<?php

namespace KodiCMS\CMS\Contracts;

interface ModelCreator
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data);

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return Model
     */
    public function create(array $data);
}
