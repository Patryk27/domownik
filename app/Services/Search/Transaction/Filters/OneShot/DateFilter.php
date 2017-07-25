<?php

namespace App\Services\Search\Transaction\Filters\OneShot;

use App\Services\Search\Filters\DateFilter as BaseDateFilter;

class DateFilter
	extends BaseDateFilter {

	/**
	 * @param string $operator
	 * @param mixed $value
	 */
	public function __construct($operator, $value) {
		parent::__construct('tpos.date', $operator, $value);
	}

}