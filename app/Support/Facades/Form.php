<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Form
	extends Facade {

	/**
	 * @return string
	 */
	public static function getFacadeAccessor() {
		return 'form';
	}

}