<?php

namespace App\Providers;

use App\Services\Install\Manager as InstallManager;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function boot() {
		parent::boot();
	}

	/**
	 * @param InstallManager $installManager
	 * @return void
	 */
	public function map(
		InstallManager $installManager
	) {
		if ($installManager->isApplicationInstalled()) {
			$this->createApplicationRoutes();
		} else {
			$this->createInstallerRoutes();
		}
	}

	/**
	 * @return void
	 */
	protected function createApplicationRoutes(): void {
		Route::middleware('web')
			 ->group(base_path('routes/web.php'));

		Route::middleware('web')
			 ->group(base_path('routes/dashboard.php'));

		Route::middleware('web')
			 ->group(base_path('routes/finances.php'));
	}

	/**
	 * @return void
	 */
	protected function createInstallerRoutes(): void {
		Route::middleware('web')
			 ->group(base_path('routes/installer.php'));
	}

}
