<?php

namespace App\Services\Module;

use App\Exceptions\BootException;
use App\Modules\ScaffoldingContract\Module\Director;
use App\Services\Module\Loader as ModuleLoader;
use App\Services\Module\Manager as ModuleManager;
use App\Support\Facades\Module;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\View;

class Initializer {

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var ModuleLoader
	 */
	protected $moduleLoader;

	/**
	 * @var ModuleManager
	 */
	protected $moduleManager;

	/**
	 * @param ModuleLoader $moduleLoader
	 * @param ModuleManager $moduleManager
	 */
	public function __construct(
		Application $app,
		ModuleLoader $moduleLoader,
		ModuleManager $moduleManager
	) {
		$this->app = $app;
		$this->moduleLoader = $moduleLoader;
		$this->moduleManager = $moduleManager;
	}

	/**
	 * @return $this
	 */
	public function initializeApplication(): self {
		$this->moduleManager->scanModules();

		$modules = [];

		foreach ($this->moduleManager->getFoundModuleNames() as $moduleName) {
			$modules[$moduleName] = $this->moduleLoader->loadModuleByName($moduleName);
		}

		if (empty($modules)) {
			throw new BootException('Found no active modules.');
		}

		View::share('enabledModules', $modules);

		$activeModule = $modules['Finances']; // @todo this index should not be hardcoded

		$this->app->singleton(Director::class, $activeModule);

		Module::setActiveModule($activeModule); // @todo get rid of this facade
		View::share('activeModule', $activeModule); // @todo change this variable's name

		$activeModule->initialize();

		return $this;
	}

	/**
	 * @return $this
	 */
	public function initializeInstaller(): self {
		$installDirector = $this->moduleLoader->loadModuleByName('Installer');
		$installDirector->initialize();

		return $this;
	}

}