<?php

namespace App\Services\Budget;

use App\ValueObjects\Budget\Summary as BudgetSummary;

interface SummaryGeneratorContract {

	/**
	 * @param int $year
	 * @return $this
	 */
	public function setYear(int $year);

	/**
	 * @param int $month
	 * @return $this
	 */
	public function setMonth(int $month);

	/**
	 * @param int $budgetId
	 * @return $this
	 */
	public function setBudgetId(int $budgetId);

	/**
	 * @return BudgetSummary
	 */
	public function generateSummary(): BudgetSummary;

}