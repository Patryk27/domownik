<?php

namespace App\Providers;

use App\Services\Install\Manager as InstallManager;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function register() {
		$this->app->singleton(\App\Services\Breadcrumb\Manager::class);
		$this->app->singleton(InstallManager::class);

		if ($this->app->environment() !== 'production') {
			$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
		}
	}

	/**
	 * @return void
	 */
	public function boot() {
		// @todo should not be hard-coded
		date_default_timezone_set('Europe/Warsaw');
		Carbon::setLocale('pl');
		app()->setLocale('pl');

		setlocale(LC_TIME, 'pl', 'pl-PL');
	}

}
