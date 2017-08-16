<?php

namespace App\Services\Budget\SummaryGenerator;

use App\Models\Transaction;
use App\Services\Search\Transaction\ScheduleSearchContract as TransactionScheduleSearchContract;
use App\ValueObjects\CarbonRange;
use App\ValueObjects\ScheduledTransaction;
use Illuminate\Support\Collection;

/**
 * This class processes transaction schedule, checking which ones can be added to the summary.
 * Contrary to the @see OneShotProcessor, this class does not have to do any range-checking because the schedule table
 * by the design contains only future rows.
 */
class ScheduleProcessor {

	/**
	 * @var TransactionScheduleSearchContract
	 */
	protected $transactionScheduleSearch;

	/**
	 * @var CarbonRange
	 */
	protected $monthRange;

	/**
	 * @var int
	 */
	protected $budgetId;

	/**
	 * @param TransactionScheduleSearchContract $transactionScheduleSearch
	 */
	public function __construct(
		TransactionScheduleSearchContract $transactionScheduleSearch
	) {
		$this->transactionScheduleSearch = $transactionScheduleSearch;
	}

	/**
	 * @return Collection|ScheduledTransaction[]
	 */
	public function processAndGetItems(): Collection {
		$this->transactionScheduleSearch
			->parent(Transaction::PARENT_TYPE_BUDGET, $this->budgetId)
			->date('>=', $this->monthRange->getRangeBegin())
			->date('<=', $this->monthRange->getRangeEnd());

		return $this->transactionScheduleSearch->get();
	}

	/**
	 * @return CarbonRange
	 */
	public function getMonthRange(): CarbonRange {
		return $this->monthRange;
	}

	/**
	 * @param CarbonRange $monthRange
	 * @return $this
	 */
	public function setMonthRange(CarbonRange $monthRange) {
		$this->monthRange = $monthRange;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getBudgetId(): int {
		return $this->budgetId;
	}

	/**
	 * @param int $budgetId
	 * @return $this
	 */
	public function setBudgetId(int $budgetId) {
		$this->budgetId = $budgetId;
		return $this;
	}

}