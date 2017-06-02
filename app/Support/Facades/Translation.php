<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Translation
	extends Facade {

	/**
	 * @return string
	 */
	public static function getFacadeAccessor() {
		return 'translation';
	}

}