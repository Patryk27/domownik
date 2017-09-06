<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider
	extends ServiceProvider {

	/**
	 * @return void
	 */
	public function boot(): void {
		$monolog = Log::getMonolog();
		$monolog->pushProcessor(new \Monolog\Processor\IntrospectionProcessor(\Monolog\Logger::DEBUG, [
			'Illuminate\\',
			'Laravel\\',
			'App\\Classes\\Logger\\Standard',
		]));

		$this->app->bind(\App\Services\Logger\Contract::class, \App\Services\Logger\Standard::class);
	}

}