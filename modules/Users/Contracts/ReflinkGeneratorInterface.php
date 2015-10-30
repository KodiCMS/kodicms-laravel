<?php
namespace KodiCMS\Users\Contracts;

use KodiCMS\Users\Model\User;
use KodiCMS\Users\Model\UserReflink;

interface ReflinkGeneratorInterface
{

    /**
     * @return string
     */
    public function getHandlerClass();


    /**
     * @return User
     */
    public function getUser();


    /**
     * @return array
     */
    public function getProperties();


    /**
     * @param UserReflink $reflink
     */
    public function tokenGenerated(UserReflink $reflink);
}