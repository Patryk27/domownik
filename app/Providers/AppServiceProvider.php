<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function register() {
		$this->app->singleton(\App\Services\Breadcrumb\Manager::class);
		$this->app->singleton(\App\Services\Install\Manager::class);
		$this->app->singleton(\App\Services\Section\ManagerContract::class, \App\Services\Section\Manager::class);
		$this->app->singleton(\App\Services\Sidebar\ManagerContract::class, \App\Services\Sidebar\Manager::class);
		$this->app->singleton(\App\Services\Sidebar\ParserContract::class, \App\Services\Sidebar\Parser::class);

		View::composer('*', \App\Http\ViewComposers\SectionComposer::class);

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
