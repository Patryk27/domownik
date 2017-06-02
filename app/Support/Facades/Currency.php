<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Currency
	extends Facade {

	/**
	 * @return string
	 */
	public static function getFacadeAccessor() {
		return 'currency';
	}

}