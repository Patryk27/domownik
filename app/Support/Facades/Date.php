<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Date
	extends Facade {

	/**
	 * @return string
	 */
	public static function getFacadeAccessor(): string {
		return \App\Support\Classes\Date::class;
	}

}