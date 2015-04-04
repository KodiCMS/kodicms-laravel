<?php namespace KodiCMS\CMS;

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
			$this->loadConfig();
			$this->loadAssets();
			$this->_isBooted = TRUE;
		}

		return $this;
	}
}