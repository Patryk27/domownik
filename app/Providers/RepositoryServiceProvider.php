<?php

namespace App\Providers;

use App\Repositories\Contracts\SettingsRepositoryContract;
use App\Repositories\Eloquent\SettingsRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function register() {
		$this->app->singleton(SettingsRepositoryContract::class, function(Application $app) {
			return new SettingsRepository($app);
		});
	}

	/**
	 * @return void
	 */
	public function boot() {
	}

}
