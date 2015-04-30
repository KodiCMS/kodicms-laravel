<?php

use KodiCMS\Users\Model\User;

/**
 * @param string|array $action
 * @param User $user
 * @return bool
 */
function acl_check($action, User $user = NULL)
{
	return \KodiCMS\Users\ACL::check($action, $user);
}