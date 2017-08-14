<?php

namespace App\Services\Budget;

use App\Exceptions\ValidationException;
use App\Services\Search\Transaction\OneShotSearchContract as TransactionOneShotSearchContract;
use App\Services\Search\Transaction\ScheduleSearchContract as TransactionScheduleSearchContract;
use App\Services\ValueObjects\EstimatedCostBuilder;
use App\ValueObjects\Budget\Summary as BudgetSummary;
use App\ValueObjects\EstimatedCost;
use Carbon\Carbon;

class SummaryGenerator
	implements SummaryGeneratorContract {

	/**
	 * @var TransactionOneShotSearchContract
	 */
	protected $transactionOneShotSearch;

	/**
	 * @var TransactionScheduleSearchContract
	 */
	protected $transactionScheduleSearch;

	/**
	 * @var int
	 */
	protected $year;

	/**
	 * @var int
	 */
	protected $month;

	/**
	 * @var int
	 */
	protected $budgetId;

	/**
	 * @var Carbon
	 */
	protected $monthBegin;

	/**
	 * @var Carbon
	 */
	protected $monthEnd;

	/**
	 * @var EstimatedCost[][]
	 */
	protected $dailyData;

	/**
	 * @param TransactionOneShotSearchContract $transactionOneShotSearch
	 * @param TransactionScheduleSearchContract $transactionScheduleSearch
	 */
	public function __construct(
		TransactionOneShotSearchContract $transactionOneShotSearch,
		TransactionScheduleSearchContract $transactionScheduleSearch
	) {
		$this->transactionOneShotSearch = $transactionOneShotSearch;
		$this->transactionScheduleSearch = $transactionScheduleSearch;
	}

	/**
	 * @inheritDoc
	 */
	public function setYear(int $year) {
		$this->year = $year;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function setMonth(int $month) {
		$this->month = $month;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function setBudgetId(int $budgetId) {
		$this->budgetId = $budgetId;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function generateSummary(): BudgetSummary {
		$this->validate();

		$this->monthBegin = Carbon::create($this->year, $this->month, 1, 0, 0, 0);
		$this->monthEnd = (clone $this->monthBegin)->endOfMonth();

		$this->dailyData = [];

		$this->addOneShotTransactions()
			 ->addScheduledTransactions();

		return $this->prepareSummary();
	}

	/**
	 * @return $this
	 * @throws ValidationException
	 */
	protected function validate() {
		if (is_null($this->year)) {
			throw new ValidationException('Year has not been set.');
		}

		if (is_null($this->month)) {
			throw new ValidationException('Month has not been set.');
		}

		if (is_null($this->budgetId)) {
			throw new ValidationException('Month has not been set.');
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function addOneShotTransactions() {
		$this->transactionOneShotSearch
			->date('>=', $this->monthBegin)
			->date('<=', $this->monthEnd);

		$oneShotTransactions = $this->transactionOneShotSearch->get();

		// @todo

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function addScheduledTransactions() {
		$this->transactionScheduleSearch
			->date('>=', $this->monthBegin)
			->date('<=', $this->monthEnd);

		$scheduledTransactions = $this->transactionScheduleSearch->get();

		foreach ($scheduledTransactions as $scheduledTransaction) {
			$dayNumber = $scheduledTransaction->getDate()->day;

			if (!isset($this->dailyData[$dayNumber])) {
				$this->dailyData[$dayNumber] = [];
			}

			$this->dailyData[$dayNumber][] = EstimatedCost::build($scheduledTransaction->getTransaction());
		}

		return $this;
	}

	/**
	 * @return BudgetSummary
	 */
	protected function prepareSummary(): BudgetSummary {
		$estimatedIncome = new EstimatedCostBuilder();
		$estimatedExpense = new EstimatedCostBuilder();

		foreach ($this->dailyData as $dayId => $costs) {
			foreach ($costs as $cost) {
				if ($cost->getEstimateMin() <= 0) {
					$estimatedExpense->addEstimateCost($cost);
				} else {
					$estimatedIncome->addEstimateCost($cost);
				}
			}
		}

		$estimatedProfit = new EstimatedCost(
			$estimatedIncome->getEstimateMin() + $estimatedExpense->getEstimateMin(),
			$estimatedIncome->getEstimateMax() + $estimatedExpense->getEstimateMax()
		);

		return new BudgetSummary([
			'year' => $this->year,
			'month' => $this->month,
			'estimatedIncome' => $estimatedIncome->build(),
			'estimatedExpense' => $estimatedExpense->build(),
			'estimatedProfit' => $estimatedProfit,
		]);
	}

}