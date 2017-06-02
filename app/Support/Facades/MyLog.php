<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class MyLog
	extends Facade {

	/**
	 * @return string
	 */
	public static function getFacadeAccessor() {
		return 'mylog';
	}

}