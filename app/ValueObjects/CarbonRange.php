<?php

namespace App\ValueObjects;

use Carbon\Carbon;

class CarbonRange {

	/**
	 * @var Carbon
	 */
	protected $rangeBegin;

	/**
	 * @var Carbon
	 */
	protected $rangeEnd;

	/**
	 * @param Carbon $rangeBegin
	 * @param Carbon $rangeEnd
	 */
	public function __construct(
		Carbon $rangeBegin,
		Carbon $rangeEnd
	) {
		$this->rangeBegin = $rangeBegin;
		$this->rangeEnd = $rangeEnd;
	}

	/**
	 * @return Carbon
	 */
	public function getRangeBegin(): Carbon {
		return $this->rangeBegin;
	}

	/**
	 * @return Carbon
	 */
	public function getRangeEnd(): Carbon {
		return $this->rangeEnd;
	}

}