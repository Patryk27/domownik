<?php

namespace App\ValueObjects;

use App\Exceptions\ValueObjectException;

trait HasOverwrite {

	/**
	 * @param array $data
	 * @throws ValueObjectException
	 */
	public function overwriteWith(array $data) {
		$result = clone $this;

		foreach ($data as $key => $value) {
			if (!property_exists($result, $key)) {
				throw new ValueObjectException('Property [%s] does not exist in class [%s].', $key, get_class($this));
			}

			$result->$key = $value;
		}

		return $result;
	}

}