<?php

namespace App\Providers;

use App\Modules\ScaffoldingContract\Module\Director;
use App\Support\Facades\Module;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\Module\Manager as ModuleManager;

class ModuleServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function register() {
		$this->app->singleton(ModuleManager::class, function($app) {
			return new ModuleManager($app);
		});
	}

	/**
	 * @param ModuleManager $moduleManager
	 * @return void
	 * @throws \App\Exceptions\BootException
	 */
	public function boot(ModuleManager $moduleManager) {
		$moduleManager->preloadModules();

		// boot all the modules
		foreach ($moduleManager->getEnabledModules() as $module) {
			$module->boot();
		}

		// find and initialize active module
		// @todo initialize actually active module (by path/middleware maybe?)
		$activeModule = $moduleManager->getEnabledModules()[1];

		$this->app->singleton(Director::class, $activeModule);

		Module::setActiveModule($activeModule);
		View::share('activeModule', $activeModule);

		$activeModule->initialize();
	}

}