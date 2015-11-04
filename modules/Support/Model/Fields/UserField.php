<?php

namespace KodiCMS\Support\Model\Fields;

use KodiCMS\Users\Model\User;

class UserField extends RelatedField
{
    /**
     * @var string
     */
    protected $keyField = 'id';

    /**
     * @var string
     */
    protected $valueField = 'username';

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    public function getOptions($key, $value)
    {
        return User::lists($this->valueField, $this->keyField)->all();
    }
}
