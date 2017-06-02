<?php

namespace App\Http\Requests;

trait IsLoggable {

	/**
	 * @return string
	 */
	public function __toString() {
		return base64_encode(json_encode($this->toArray()));
	}

}