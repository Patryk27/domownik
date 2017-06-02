<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
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
				'App\\Support\\Classes\\MyLog',
				'App\\Support\\Facades\\MyLog'
			]));
		}
	}

}