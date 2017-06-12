<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function register() {
		$this->app->singleton(\App\Repositories\Contracts\ModuleRepositoryContract::class, \App\Repositories\Eloquent\ModuleRepository::class);
		$this->app->singleton(\App\Repositories\Contracts\ModuleSettingRepositoryContract::class, \App\Repositories\Eloquent\ModuleSettingRepository::class);
		$this->app->singleton(\App\Repositories\Contracts\SettingRepositoryContract::class, \App\Repositories\Eloquent\SettingRepository::class);
	}

	/**
	 * @return void
	 */
	public function boot() {
	}

}
