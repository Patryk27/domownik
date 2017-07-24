<?php

namespace App\Services\Search\Filters\Transaction\OneShot;

use App\Services\Search\Filters\Common\Date as DateFilter;

class Date
	extends DateFilter {

	/**
	 * @param string $operator
	 * @param mixed $value
	 */
	public function __construct($operator, $value) {
		parent::__construct('tpos.date', $operator, $value);
	}

}