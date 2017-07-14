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
			$this->createRoutes();
		}
	}

	/**
	 * @return void
	 */
	protected function createRoutes() {
		// @todo autoload routes

		Route::middleware('web')
			 ->group(base_path('routes/web.php'));

		Route::middleware('web')
			 ->group(base_path('routes/dashboard.php'));

		Route::middleware('web')
			 ->group(base_path('routes/finances.php'));
	}

}
