<?php

namespace App\Exceptions;

class Exception
	extends \Exception {

	/**
	 * @param string $msg
	 */
	public function __construct($msg) {
		parent::__construct(call_user_func_array('sprintf', func_get_args()));
	}

}