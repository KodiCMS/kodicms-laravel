<?php namespace KodiCMS\CMS\Loader;

class AppModuleContainer extends ModuleContainer
{
	/**
	 * @var string
	 */
	protected $_namespace = '';

	/**
	 * @return $this
	 */
	public function boot()
	{
		$this->_isBooted = TRUE;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function register()
	{
		$this->_isRegistered = TRUE;
		return $this;
	}
}