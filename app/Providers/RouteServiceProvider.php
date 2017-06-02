<?php

namespace App\Providers;

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
	 * @return void
	 */
	public function map() {
		$this->mapWebRoutes();
	}

	/**
	 * @return void
	 */
	protected function mapWebRoutes() {
		Route::middleware('web')
			 ->namespace($this->namespace)
			 ->group(base_path('routes/web.php'));
	}

}
