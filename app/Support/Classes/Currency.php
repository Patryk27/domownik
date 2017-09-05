<?php

namespace App\Support\Classes;

class Currency {

	/**
	 * @param float $price
	 * @return string
	 */
	public function formatWithUnit($price) {
		// @todo 'zł' should not be hardcoded
		return sprintf('%s zł', self::format($price));
	}

	/**
	 * @param float $price
	 * @return string
	 */
	public function format($price) {
		return number_format($price, 2, '.', ' ');
	}

}