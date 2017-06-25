<?php

namespace App\Services\Module;

use App\Exceptions\ModuleLoaderException;
use App\Modules\Scaffolding\Module\DirectorContract;
use App\Modules\Scaffolding\Module\ServiceProviderContract;
use Illuminate\Foundation\Application;

class Loader {

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @param Application $app
	 */
	public function __construct(
		Application $app
	) {
		$this->app = $app;
	}

	/**
	 * Loads module of given name and returns its director.
	 * @param string $moduleName
	 * @return DirectorContract
	 */
	public function loadModuleByName(string $moduleName): DirectorContract {
		$moduleDirectoryName = app_path('Modules' . DIRECTORY_SEPARATOR . $moduleName);

		if (!is_dir($moduleDirectoryName)) {
			throw new ModuleLoaderException('Could not find directory of module \'%s\' (tried path: \'%s\').', $moduleName, $moduleDirectoryName);
		}

		$directorClassName = sprintf('\App\Modules\%s\Module\Director', $moduleName);
		$serviceProviderClassName = sprintf('\App\Modules\%s\Module\ServiceProvider', $moduleName);

		/**1
		 * Laravel would of course throw an exception if these classes were not found, but we're doing it here because
		 * it yields a more readable message of what's happening.
		 */

		if (!class_exists($directorClassName)) {
			throw new ModuleLoaderException('Could not find director class for module \'%s\' (tried class name: \'%s\').', $moduleName, $directorClassName);
		}

		if (!class_exists($serviceProviderClassName)) {
			throw new ModuleLoaderException('Could not find service provider class for module \'%s\' (tried class name: \'%s\').', $moduleName, $serviceProviderClassName);
		}

		/**
		 * @var ServiceProviderContract $serviceProvider
		 */
		$serviceProvider = $this->app->make($serviceProviderClassName);
		$serviceProvider->boot($moduleName);

		/**
		 * @var DirectorContract $director
		 */
		$director = $this->app->make($directorClassName);
		$director->boot($serviceProvider);

		return $director;
	}

}