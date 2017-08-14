<?php

namespace App\ValueObjects\Budget;

use App\ValueObjects\EstimatedCost;
use App\ValueObjects\HasInitializationConstructor;

class Summary {

	use HasInitializationConstructor;

	/**
	 * @var int
	 */
	protected $year;

	/**
	 * @var int
	 */
	protected $month;

	/**
	 * @var EstimatedCost
	 */
	protected $estimatedIncome;

	/**
	 * @var EstimatedCost
	 */
	protected $estimatedExpense;

	/**
	 * @var EstimatedCost
	 */
	protected $estimatedProfit;

	/**
	 * @var float[]
	 */
	protected $dailyIncome;

	/**
	 * @var float[]
	 */
	protected $dailyExpenses;

	/**
	 * @var float[]
	 */
	protected $dailyProfit;

	/**
	 * @return int
	 */
	public function getYear(): int {
		return $this->year;
	}

	/**
	 * @return int
	 */
	public function getMonth(): int {
		return $this->month;
	}

	/**
	 * @return EstimatedCost
	 */
	public function getEstimatedIncome(): EstimatedCost {
		return $this->estimatedIncome;
	}

	/**
	 * @return EstimatedCost
	 */
	public function getEstimatedExpense(): EstimatedCost {
		return $this->estimatedExpense;
	}

	/**
	 * @return EstimatedCost
	 */
	public function getEstimatedProfit(): EstimatedCost {
		return $this->estimatedProfit;
	}

	/**
	 * @return float[]
	 */
	public function getDailyIncome(): array {
		return $this->dailyIncome;
	}

	/**
	 * @return float[]
	 */
	public function getDailyExpenses(): array {
		return $this->dailyExpenses;
	}

	/**
	 * @return float[]
	 */
	public function getDailyProfit(): array {
		return $this->dailyProfit;
	}

}