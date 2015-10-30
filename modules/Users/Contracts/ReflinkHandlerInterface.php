<?php
namespace KodiCMS\Users\Contracts;

use KodiCMS\Users\Model\UserReflink;

interface ReflinkHandlerInterface
{

    /**
     * @param UserReflink $reflink
     */
    public function __construct(UserReflink $reflink);


    /**
     * @return string
     */
    public function getResponse();


    public function handle();
}