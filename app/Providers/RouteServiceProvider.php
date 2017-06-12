<?php

namespace App\Providers;

use App\Services\Install\Manager as InstallManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider
	extends ServiceProvider {

	/**
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

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
		Route::middleware('web')
			 ->namespace($this->namespace)
			 ->group(base_path('routes/web.php'));
	}

}
