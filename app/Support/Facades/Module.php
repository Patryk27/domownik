<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Module
	extends Facade {

	/**
	 * @return string
	 */
	public static function getFacadeAccessor() {
		return 'module';
	}

}