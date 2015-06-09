<?php namespace KodiCMS\CMS;

use CMS;

class ModuleContainer extends Loader\ModuleContainer
{
	/**
	 * @param \Illuminate\Foundation\Application $app
	 * @return $this
	 */
	public function register($app)
	{
		if (!$this->isRegistered)
		{
			$this->isRegistered = true;
		}

		return $this;
	}
}