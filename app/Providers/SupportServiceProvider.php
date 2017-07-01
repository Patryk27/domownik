<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class SupportServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function boot() {
		$this->app->bind('breadcrumb', function(Application $app) {
			return $app->make(\App\Support\Classes\Breadcrumb::class);
		});

		$this->app->bind('calendar', function(Application $app) {
			return $app->make(\App\Support\Classes\Calendar::class);
		});

		$this->app->bind('configuration', function(Application $app) {
			return $app->make(\App\Support\Classes\Configuration::class);
		});

		$this->app->bind('controller', function(Application $app) {
			return $app->make(\App\Support\Classes\Controller::class);
		});

		$this->app->bind('currency', function(Application $app) {
			return $app->make(\App\Support\Classes\Currency::class);
		});

		$this->app->bind('date', function(Application $app) {
			return $app->make(\App\Support\Classes\Date::class);
		});

		$this->app->bind('form', function(Application $app) {
			return $app->make(\App\Support\Classes\Form::class);
		});

		$this->app->bind('translation', function(Application $app) {
			return $app->make(\App\Support\Classes\Translation::class);
		});

		$this->app->bind('module', function(Application $app) {
			return $app->make(\App\Support\Classes\Module::class);
		});

		$this->app->bind('utils', function(Application $app) {
			return $app->make(\App\Support\Classes\Utils::class);
		});
	}

}
