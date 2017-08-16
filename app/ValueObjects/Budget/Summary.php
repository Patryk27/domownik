<?php

namespace App\ValueObjects\Budget;

use App\ValueObjects\EstimatedCost;
use App\ValueObjects\HasInitializationConstructor;
use App\ValueObjects\ScheduledTransaction;
use Illuminate\Support\Collection;

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
	 * @var Collection|ScheduledTransaction[]
	 */
	protected $transactions;

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
	 * @return Collection|ScheduledTransaction[]
	 */
	public function getTransactions(): Collection {
		return $this->transactions;
	}

}