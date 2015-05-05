<?php namespace KodiCMS\CMS\Contracts;

interface ModuleContainerInterface
{
	/**
	 * @param string $moduleName
	 * @param null|string $modulePath
	 * @param null|string $namespace
	 */
	public function __construct($moduleName, $modulePath = null, $namespace = null);

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getNamespace();

	/**
	 * @return string
	 */
	public function getControllerNamespace();

	/**
	 * @param strimg|null $sub
	 * @return string
	 */
	public function getPath($sub = null);

	/**
	 * @return string
	 */
	public function getLocalePath();

	/**
	 * @return string
	 */
	public function getViewsPath();

	/**
	 * @return string
	 */
	public function getConfigPath();

	/**
	 * @return string
	 */
	public function getAssetsPackagesPath();

	/**
	 * @return string
	 */
	public function getRoutesPath();

	/**
	 * @return string
	 */
	public function getServiceProviderPath();
//
//	/**
//	 * @return $this
//	 */
//	public function boot();
//
//	/**
//	 * @return $this
//	 */
//	public function register();
}