<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Calendar
	extends Facade {

	/**
	 * @return string
	 */
	public static function getFacadeAccessor(): string {
		return \App\Support\Classes\Calendar::class;
	}

}