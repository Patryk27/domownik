<?php

namespace App\Services\Budget;

use App\Exceptions\UserInterfaceException;
use App\Exceptions\ValidationException;
use App\Services\Budget\SummaryGenerator\OneShotProcessor;
use App\Services\Budget\SummaryGenerator\ScheduleProcessor;
use App\Services\ValueObjects\EstimatedCostBuilder;
use App\ValueObjects\Budget\Summary as BudgetSummary;
use App\ValueObjects\CarbonRange;
use App\ValueObjects\EstimatedCost;
use App\ValueObjects\ScheduledTransaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SummaryGenerator
	implements SummaryGeneratorContract {

	/**
	 * @var OneShotProcessor
	 */
	protected $oneShotProcessor;

	/**
	 * @var ScheduleProcessor
	 */
	protected $scheduleProcessor;

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
	 * @var CarbonRange
	 */
	protected $monthRange;

	/**
	 * @var Collection|EstimatedCost[][]
	 */
	protected $dailyCost;

	/**
	 * @var Collection|ScheduledTransaction[]
	 */
	protected $transactions;

	/**
	 * @param OneShotProcessor $oneShotProcessor
	 * @param ScheduleProcessor $scheduleProcessor
	 */
	public function __construct(
		OneShotProcessor $oneShotProcessor,
		ScheduleProcessor $scheduleProcessor
	) {
		$this->oneShotProcessor = $oneShotProcessor;
		$this->scheduleProcessor = $scheduleProcessor;
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
		$this
			->validate()
			->validateDate()
			->prepare()
			->addOneShotTransactions()
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
			throw new ValidationException('Budget it has not been set.');
		}

		return $this;
	}

	/**
	 * @return $this
	 * @throws UserInterfaceException
	 */
	protected function validateDate() {
		$summaryDate = Carbon::create($this->year, $this->month, 31, 0, 0, 0);

		/**
		 * As the 'transaction_schedules' table is created only with a year of spare time, we cannot show summary which
		 * is further in past than a year.
		 */

		$summaryMaximumDate =
			Carbon::now()
				  ->addYear();

		if ($summaryDate->greaterThanOrEqualTo($summaryMaximumDate)) {
			throw new UserInterfaceException(__('requests/budget/summary.messages.too-far-into-future'));
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function prepare() {
		$monthBegin = Carbon::create($this->year, $this->month, 1, 0, 0, 0);

		$this->monthRange = new CarbonRange(
			$monthBegin,
			(clone $monthBegin)->endOfMonth()
		);

		$this->dailyCost = new Collection();
		$this->transactions = new Collection();

		for ($i = 1; $i <= 31; ++$i) {
			$this->dailyCost[$i] = new Collection();
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function addOneShotTransactions() {
		$this->oneShotProcessor
			->setMonthRange($this->monthRange)
			->setBudgetId($this->budgetId);

		$this->addTransactions($this->oneShotProcessor->processAndGetItems());

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function addScheduledTransactions() {
		$this->scheduleProcessor
			->setMonthRange($this->monthRange)
			->setBudgetId($this->budgetId);

		$this->addTransactions($this->scheduleProcessor->processAndGetItems());

		return $this;
	}

	/**
	 * @param Collection|ScheduledTransaction[]|null $scheduledTransactions
	 * @return $this
	 */
	protected function addTransactions(?Collection $scheduledTransactions) {
		if (!isset($scheduledTransactions)) {
			return $this;
		}

		foreach ($scheduledTransactions as $scheduledTransaction) {
			$this->dailyCost
				->get($scheduledTransaction->getDate()->day)
				->push(EstimatedCost::build($scheduledTransaction->getTransaction()));

			$this->transactions->push($scheduledTransaction);
		}

		return $this;
	}

	/**
	 * @return BudgetSummary
	 */
	protected function prepareSummary(): BudgetSummary {
		$estimatedIncome = new EstimatedCostBuilder();
		$estimatedExpense = new EstimatedCostBuilder();

		foreach ($this->dailyCost as $day => $dayCosts) {
			foreach ($dayCosts as $cost) {
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
			'transactions' => $this->transactions,
		]);
	}

}