<?php namespace KodiCMS\Plugins\Loader;

use Event;
use KodiCMS\CMS\Core as CMS;
use KodiCMS\Plugins\Model\Plugin;
use KodiCMS\Support\Traits\Settings;
use KodiCMS\CMS\Loader\ModuleContainer;
use KodiCMS\Installer\Support\ModuleInstaller;
use KodiCMS\Plugins\Exceptions\PluginContainerException;
use KodiCMS\Support\Facades\PluginLoader as PluginLoaderFacade;

abstract class BasePluginContainer extends ModuleContainer
{
	use Settings;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var bool
	 */
	protected $isInstallable = true;

	/**
	 * @var bool
	 */
	protected $isActivated = false;

	/**
	 * @var array
	 */
	protected $details = [];

	/**
	 * @param string $moduleName
	 * @param null|string $modulePath
	 * @param null|string $namespace
	 * @throws PluginContainerException
	 */
	public function __construct($moduleName, $modulePath = null, $namespace = null)
	{
		parent::__construct($moduleName, $modulePath, $namespace);

		$this->details = array_merge($this->defaultDetails(), $this->details());

		if (!isset($this->details['title']))
		{
			throw new PluginContainerException("Plugin title for plugin {$moduleName} not set");
		}

		$this->isInstallable = $this->checkPluginVersion();
		$this->isActivated = in_array($this, PluginLoaderFacade::getActivated());
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return array_get($this->details, 'title');
	}

	/**
	 * @return string
	 */
	public function getAuthor()
	{
		return array_get($this->details, 'author');
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return array_get($this->details, 'description');
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return array_get($this->details, 'icon');
	}

	/**
	 * @return boolean
	 */
	public function isInstallable()
	{
		return $this->isInstallable;
	}

	/**
	 * @return boolean
	 */
	public function isActivated()
	{
		return $this->isActivated;
	}

	/**
	 * @return string
	 */
	public function getVersion()
	{
		return array_get($this->details, 'version', '0.0.0');
	}

	/**
	 * @return string
	 */
	public function getRequiredVersion()
	{
		return array_get($this->details, 'required_cms_version', '0.0.0');
	}

	/**
	 * @return bool
	 */
	public function checkActivation()
	{
		return $this->isActivated = in_array($this, PluginLoaderFacade::getActivated());
	}

	/**
	 * @return bool
	 * @throws PluginContainerException
	 */
	public function activate()
	{
		if ($this->isActivated())
		{
			throw new PluginContainerException("Plugin is activated");
		}

		if (!$this->isInstallable())
		{
			$CmsVersion = CMS::VERSION;
			throw new PluginContainerException("
				Plugin can`t be installed.
				Required CMS version is: [{$this->getRequiredVersion()}]
				Version of your CMS is: [{$CmsVersion}].
			");
		}

		Plugin::create([
			'key' => $this->getName(),
			'settings' => $this->getSettings()
		]);

		$installer = new ModuleInstaller([]);
		$installer->migrateModule($this);
		$installer->seedModule($this);

		event('plugin.activate', [$this->getName()]);

		$this->isActivated = true;

		return true;
	}

	/**
	 * @param bool $removeTable
	 * @return bool
	 * @throws PluginContainerException
	 */
	public function deactivate($removeTable = false)
	{
		if (!$this->isActivated())
		{
			throw new PluginContainerException("Plugin is not activated");
		}

		if (is_null($plugin = Plugin::where('key', $this->getName())))
		{
			throw new PluginContainerException("Plugin not found");
		}

		$plugin->delete();

		if ($removeTable)
		{
			// TODO сделать удаление данных из БД
			$installer = new ModuleInstaller([]);
			$installer->addModuleToReset($this);
			$installer->rollbackModules();
		}

		event('plugin.deactivate', [$this->getName()]);

		$this->isActivated = false;

		return true;
	}

	/**
	 * @return bool
	 */
	protected function checkPluginVersion()
	{
		return version_compare(CMS::VERSION, $this->getRequiredVersion(), '>=');
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$details = $this->details;

		$details['isInstallable'] = $this->isInstallable();
		$details['isActivated'] = $this->isActivated();
		$details['settings'] = $this->getSettings();

		return array_merge(parent::toArray(), $details);
	}

	/**
	 * @param string $moduleName
	 * @return string
	 */
	protected function getDefaultModulePath($moduleName)
	{
		return base_path('plugins/' . $moduleName);
	}

	/**
	 * @return string
	 */
	protected function publishViewPath()
	{
		return base_path("/resources/views/plugins/{$this->getName()}");
	}

	/**
	 * @return array
	 */
	protected function defaultDetails()
	{
		return [
			'title' 				=> null,
			'description' 			=> null,
			'author' 				=> null,
			'icon' 					=> 'puzzle-piece',
			'version' 				=> '0.0.0',
			'required_cms_version'  => '0.0.0'
		];
	}

	/**
	 * @return array
	 *
	 * [
	 * 		'title' 				=> '...',
	 *		'description' 			=> '...',
	 *		'author' 				=> '...',
	 *		'icon' 					=> '...',
	 * 		'version' 				=> '...',
	 * 		'required_cms_version'  => '...'
	 * ]
	 */
	abstract public function details();
}