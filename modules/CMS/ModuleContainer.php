<?php namespace KodiCMS\CMS;

use CMS;

class ModuleContainer extends Loader\ModuleContainer
{
	/**
	 * @return $this
	 */
	public function register()
	{
		if (!$this->_isRegistered)
		{
			$this->_isRegistered = true;
		}

		return $this;
	}
}