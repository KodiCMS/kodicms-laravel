<?php namespace KodiCMS\API\Exceptions;

use KodiCMS\API\Http\Response;

class PermissionException extends Exception
{

	/**
	 * @var string
	 */
	protected $permission = '';

	/**
	 * @param null $permission
	 * @param string $message
	 */
	public function __construct($permission = null, $message = "")
	{
		$this->setPermission($permission);
		$this->message = empty($message) ? trans('api::core.messages.error_permissions') : $message;
	}

	/**
	 * @param $permission
	 */
	public function setPermission($permission)
	{
		$this->permission = $permission;
	}

	/**
	 * @return array
	 */
	public function responseArray()
	{
		return [
			'code' => Response::ERROR_PERMISSIONS,
			'type' => Response::TYPE_ERROR,
			'permission' => $this->permission
		];
	}

}
