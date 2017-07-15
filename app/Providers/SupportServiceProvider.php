<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class SupportServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function boot() {
		$this->app->bind('breadcrumb', \App\Support\Classes\Breadcrumb::class);
		$this->app->bind('calendar', \App\Support\Classes\Calendar::class);
		$this->app->bind('configuration', \App\Support\Classes\Configuration::class);
		$this->app->bind('controller', \App\Support\Classes\Controller::class);
		$this->app->bind('currency', \App\Support\Classes\Currency::class);
		$this->app->bind('date', \App\Support\Classes\Date::class);
		$this->app->bind('form', \App\Support\Classes\Form::class);
		$this->app->bind('utils', \App\Support\Classes\Utils::class);
	}

}
