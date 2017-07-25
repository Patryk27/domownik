<?php

namespace App\Services\Search\Transaction\Filters;

use App\Services\Search\Filters\StringFilter as BaseStringFilter;

class NameFilter
	extends BaseStringFilter {

	/**
	 * @param string $operator
	 * @param string $value
	 */
	public function __construct($operator, $value) {
		parent::__construct('t.name', $operator, $value);
	}

}