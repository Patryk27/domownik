<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\StreamHandler;

class LogServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function boot() {
		$monolog = Log::getMonolog();

		if (App::runningInConsole()) {
			$monolog->pushHandler(new StreamHandler('php://stdout'));
		} else {
			// @todo temporary solution - ultimately I'd like to log to file with introspection processor but do not use
			//	it when writing to console.
			$monolog->pushProcessor(new \Monolog\Processor\IntrospectionProcessor(\Monolog\Logger::DEBUG, [
				'Illuminate\\',
				'Laravel\\',
				'App\\Classes\\Logger\\Standard',
			]));
		}

		$this->app->bind(\App\Services\Logger\Contract::class, \App\Services\Logger\Standard::class);
	}

}