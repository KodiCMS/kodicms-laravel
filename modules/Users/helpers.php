<?php

use KodiCMS\Users\Model\User;

function acl_check($action, User $user = NULL)
{
	return TRUE;
	// TODO: выключить фековую проверку прав
	//return \KodiCMS\Users\ACL::check($action, $user);
}