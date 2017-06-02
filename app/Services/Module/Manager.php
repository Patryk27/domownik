<?php

namespace App\Services\Module;

use App\Exceptions\BootException;
use App\Models\Module;
use App\Models\ModuleSetting;
use App\Modules\ScaffoldingContract\Module\Director;
use App\Modules\ScaffoldingContract\Module\ServiceProvider;
use App\Support\Facades\Log;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class Manager {

	/**
	 * @var \Illuminate\Foundation\Application
	 */
	protected $app;

	/**
	 * Contains all modules (except Scaffolding*) loaded from the \app\Modules directory.
	 * Maps module name to module director instance.
	 * @var Director[]
	 */
	protected $presentModules;

	/**
	 * Contains only loaded modules, based on $allModules.
	 * Maps module name to module directory instance.
	 * @var Director[]
	 */
	protected $enabledModules;

	/**
	 * Manager constructor.
	 * @param \Illuminate\Foundation\Application $app
	 */
	public function __construct(\Illuminate\Foundation\Application $app) {
		$this->app = $app;
	}

	/**
	 * @return $this
	 * @throws \App\Exceptions\BootException
	 */
	public function preloadModules() {
		$this
			->loadModules()
			->enableModules();

		return $this;
	}

	/**
	 * Returns all modules that are present in the modules directory.
	 * @return Director[]
	 */
	public function getPresentModules() {
		return $this->presentModules;
	}

	/**
	 * Returns all modules that were enabled in the configuration.
	 * @return Director[]
	 */
	public function getEnabledModules() {
		return $this->enabledModules;
	}

	/**
	 * Returns modules' directory path.
	 * @return string
	 */
	public static function getModulesDirectory(): string {
		return app_path('Modules');
	}

	/**
	 * Returns specific module's directory.
	 * @param string $moduleName
	 * @param string $path
	 * @return string
	 */
	public static function getModuleDirectory(string $moduleName, string $path): string {
		$result = self::getModulesDirectory() . DIRECTORY_SEPARATOR . $moduleName;

		if (!empty($path)) {
			$result .= DIRECTORY_SEPARATOR . $path;
		}

		return $result;
	}

	/**
	 * Loads every module present in the Modules directory.
	 * @return $this
	 */
	protected function loadModules() {
		// @todo cache

		$this->presentModules = [];
		$this->enabledModules = [];

		$modulesDir = self::getModulesDirectory() . DIRECTORY_SEPARATOR;
		$modulePaths = glob($modulesDir . '*');

		foreach ($modulePaths as $modulePath) {
			$moduleName = Collection::make(explode(DIRECTORY_SEPARATOR, $modulePath))
									->last();

			$moduleDirectorClassName = sprintf('\App\Modules\%s\Module\Director', $moduleName);
			$moduleServiceProviderClassName = sprintf('\App\Modules\%s\Module\ServiceProvider', $moduleName);

			// do not load the dummy base modules
			if (in_array($moduleName, ['Scaffolding', 'ScaffoldingContract'], true)) {
				continue;
			}

			/**
			 * @var ServiceProvider $serviceProvider
			 */
			$serviceProvider = $this->app->make($moduleServiceProviderClassName);
			$serviceProvider
				->setModuleName($moduleName)
				->boot();

			$this->presentModules[$moduleName] = $this->app->make($moduleDirectorClassName);
		}

		return $this;
	}

	/**
	 * @return $this
	 * @throws BootException
	 */
	protected function enableModules() {
		$enabledModules = Module::all()
								->keyBy('name');

		$this->enabledModules = [];

		foreach ($this->presentModules as $moduleDirector) {
			// if module is present in the database, check its configuration
			if ($enabledModules->has($moduleDirector->getName())) {
				$moduleId = $enabledModules->get($moduleDirector->getName())->id;

				$isModuleEnabled = ModuleSetting::getSettingValue($moduleId, 'is-enabled');

				if ($isModuleEnabled) {
					$this->enabledModules[] = $moduleDirector;
				}
			} else {
				$moduleModel = new Module();
				$moduleModel->name = $moduleDirector->getName();
				$moduleModel->saveOrFail();
			}
		}

		if (empty($this->enabledModules)) {
			throw new BootException('No modules are enabled.');
		}

		View::share('enabledModules', $this->enabledModules);
		return $this;
	}

}