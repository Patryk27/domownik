<?php

namespace App\Exceptions;

class UserInterfaceException
	extends Exception {

	/**
	 * @param string $msg
	 */
	public function __construct(string $msg) {
		parent::__construct('%s', $msg);
	}

}