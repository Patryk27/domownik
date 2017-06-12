<?php

namespace App\Services\Module;


use App\Models\Module;
use App\Models\ModuleSetting;

use App\Repositories\Contracts\ModuleRepositoryContract;
use App\Repositories\Contracts\ModuleSettingRepositoryContract;
use App\Support\Classes\MyLog;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;

class Manager {

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var MyLog
	 */
	protected $myLog;

	/**
	 * @var ModuleRepositoryContract
	 */
	protected $moduleRepository;

	/**
	 * @var ModuleSettingRepositoryContract
	 */
	protected $moduleSettingRepository;

	/**
	 * @var string[]
	 */
	protected $foundModuleNames;

	/**
	 * List of modules which should not be loaded.
	 * @var string[]
	 */
	protected $modulesToSkip = [
		'Installer',
		'Scaffolding',
		'ScaffoldingContract',
	];

	/**
	 * Manager constructor.
	 * @param Application $app
	 * @param MyLog $myLog
	 * @param ModuleRepositoryContract $moduleRepository
	 * @param ModuleSettingRepositoryContract $moduleSettingRepository
	 */
	public function __construct(
		Application $app,
		MyLog $myLog,
		ModuleRepositoryContract $moduleRepository,
		ModuleSettingRepositoryContract $moduleSettingRepository
	) {
		$this->app = $app;
		$this->myLog = $myLog;
		$this->moduleRepository = $moduleRepository;
		$this->moduleSettingRepository = $moduleSettingRepository;
	}

	/**
	 * @return $this
	 */
	public function scanModules(): self {
		// @todo cache

		$modulesDir = self::getModulesDirectory() . DIRECTORY_SEPARATOR;
		$modulePaths = glob($modulesDir . '*');

		foreach ($modulePaths as $modulePath) {
			$moduleName =
				Collection::make(explode(DIRECTORY_SEPARATOR, $modulePath))
						  ->last();

			$this->checkModule($moduleName);
		}

		return $this;
	}

	/**
	 * @param string $moduleName
	 * @return $this
	 */
	protected function checkModule(string $moduleName): self {
		if (in_array($moduleName, $this->modulesToSkip, true)) {
			return $this;
		}

		$moduleId = $this->getModuleId($moduleName);

		$isModuleEnabled = $this->getModuleSetting($moduleId, 'is-enabled', true);

		if ($isModuleEnabled) {
			$this->foundModuleNames[] = $moduleName;
		}

		return $this;
	}

	/**
	 * Returns id of given module.
	 * If given module does not exist in the database, creates it.
	 * @param string $moduleName
	 * @return int
	 */
	protected function getModuleId(string $moduleName) {
		$module = $this->moduleRepository->getByName($moduleName);

		if (empty($module)) {
			$this->myLog->notice('Module with name=\'%s\' has not been found in the database - creating one.', $moduleName);

			$module = new Module();
			$module->name = $moduleName;
			$module->save();

			$this->moduleRepository->persist($module);

			$this->myLog->notice('... created module id=%d.', $module->id);
		}

		return $module->id;
	}

	/**
	 * Returns value of given module's configuration key.
	 * Creates a default value if no value is set.
	 * @param int $moduleId
	 * @param string $settingKey
	 * @param mixed $settingDefaultValue
	 * @return mixed
	 * @todo This method is reusable, which makes ModuleManager a rather weird place to place it into.
	 */
	protected function getModuleSetting(int $moduleId, string $settingKey, $settingDefaultValue) {
		$settingValue = $this->moduleSettingRepository->getValueByKey($moduleId, $settingKey);

		if (is_null($settingValue)) {
			$this->myLog->notice('Module with id=%d does not have any value for setting=\'%s\', setting default one: \'%s\'.', $moduleId, $settingKey, json_encode($settingDefaultValue));

			$moduleSetting = new ModuleSetting();
			$moduleSetting->module_id = $moduleId;
			$moduleSetting->key = $settingKey;
			$moduleSetting->value = $settingDefaultValue;

			$this->moduleSettingRepository->persist($moduleSetting);

			$settingValue = $settingDefaultValue;
		}

		return $settingValue;
	}

	/**
	 * @return string[]
	 */
	public function getFoundModuleNames() {
		return $this->foundModuleNames;
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

}