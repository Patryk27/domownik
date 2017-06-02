<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Configuration
	extends Facade {

	/**
	 * @return string
	 */
	public static function getFacadeAccessor() {
		return 'configuration';
	}

}