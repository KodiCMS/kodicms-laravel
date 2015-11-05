<?php

namespace KodiCMS\Email\Repository;

use KodiCMS\Email\Model\EmailEvent;
use KodiCMS\CMS\Repository\BaseRepository;

class EmailEventRepository extends BaseRepository
{
    /**
     * @param EmailEvent $model
     */
    public function __construct(EmailEvent $model)
    {
        parent::__construct($model);
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
            'name' => 'required',
            'code' => 'required',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnUpdate(array $data = [])
    {
        $validator = $this->validator($data, [
            'name' => 'required',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @return array
     */
    public function eventsList()
    {
        return $this->all()->lists('fullName', 'id')->all();
    }
}
