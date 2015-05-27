<?php namespace KodiCMS\CMS;

use CMS;

class ModuleContainer extends Loader\ModuleContainer
{
	/**
	 * @return $this
	 */
	public function register()
	{
		if (!$this->isRegistered)
		{
			$this->isRegistered = true;
		}

		return $this;
	}
}