<?php

namespace App\ValueObjects;

use App\Exceptions\ValueObjectException;

trait HasInitializationConstructor {

	/**
	 * @param array $data
	 * @throws ValueObjectException
	 */
	public function __construct(array $data) {
		foreach ($data as $key => $value) {
			if (!property_exists($this, $key)) {
				throw new ValueObjectException('Property [%s] does not exist in class [%s].', $key, get_class($this));
			}

			$this->$key = $value;
		}
	}

}