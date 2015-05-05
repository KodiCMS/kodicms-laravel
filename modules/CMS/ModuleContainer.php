<?php namespace KodiCMS\CMS;

use CMS;

class ModuleContainer extends Loader\ModuleContainer
{
	/**
	 * @return $this
	 */
	public function boot()
	{
		if (!$this->_isBooted) {
			$this->loadViews();
			$this->loadTranslations();

			if (CMS::isInstalled())
			{
				$this->loadConfig();
			}

			$this->loadAssets();
			$this->_isBooted = TRUE;
		}

		return $this;
	}
}