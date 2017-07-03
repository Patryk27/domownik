<?php

namespace App\Providers;

use App\Services\Install\Manager as InstallManager;
use App\Services\Module\Initializer as ModuleInitializer;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function register() {
	}

	/**
	 * @param InstallManager $installManager
	 * @param ModuleInitializer $moduleInitializer
	 * @return void
	 */
	public function boot(
		InstallManager $installManager,
		ModuleInitializer $moduleInitializer
	) {
		if ($installManager->isApplicationInstalled()) {
			$moduleInitializer->initializeApplication();
		} else {
			$moduleInitializer->initializeInstaller();
		}
	}

}