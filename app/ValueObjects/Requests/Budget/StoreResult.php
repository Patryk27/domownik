<?php

namespace App\ValueObjects\Requests\Budget;

use App\Models\Budget;

class StoreResult {

	/**
	 * @var Budget
	 */
	protected $budget;

	/**
	 * @param Budget $budget
	 */
	public function __construct(
		Budget $budget
	) {
		$this->budget = $budget;
	}

	/**
	 * @return Budget
	 */
	public function getBudget(): Budget {
		return $this->budget;
	}

}