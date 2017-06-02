<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Breadcrumb
	extends Facade {

	/**
	 * @return string
	 */
	public static function getFacadeAccessor() {
		return 'breadcrumb';
	}

}