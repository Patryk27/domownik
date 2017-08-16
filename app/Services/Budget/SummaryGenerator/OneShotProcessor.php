<?php

namespace App\Services\Budget\SummaryGenerator;

use App\Models\Transaction;
use App\Models\TransactionPeriodicityOneShot;
use App\Services\Search\Transaction\OneShotSearchContract as TransactionOneShotSearchContract;
use App\ValueObjects\CarbonRange;
use App\ValueObjects\ScheduledTransaction;
use Carbon\Carbon;
use Date;
use Illuminate\Support\Collection;

/**
 * This class processes one shot transactions, checking which ones can be added to the summary.
 * A care is taken that date ranges don't overlap with @see ScheduleProcessor and thus this class processes only
 * transactions which are already in the past.
 */
class OneShotProcessor {

	/**
	 * @var TransactionOneShotSearchContract
	 */
	protected $transactionOneShotSearch;

	/**
	 * @var CarbonRange
	 */
	protected $monthRange;

	/**
	 * @var int
	 */
	protected $budgetId;

	/**
	 * @param TransactionOneShotSearchContract $transactionOneShotSearch
	 */
	public function __construct(
		TransactionOneShotSearchContract $transactionOneShotSearch
	) {
		$this->transactionOneShotSearch = $transactionOneShotSearch;
	}

	/**
	 * @return Collection|ScheduledTransaction[]|null
	 */
	public function processAndGetItems(): ?Collection {
		$beginDate = $this->getBeginDate();
		$endDate = $this->getEndDate();

		if ($beginDate->eq($endDate)) {
			return null;
		}

		/**
		 * If today's the end date, both the @see OneShotProcessor and @see ScheduleProcessor would include this
		 * transaction in the summary, effectively doubling it's value. To avoid this, we just sub one day from the
		 * one-shot summary (we could also do addDay() in schedule processor but it really doesn't matter).
		 */
		if ($endDate->isToday()) {
			$endDate->subDay();
		}

		$this->transactionOneShotSearch
			->parent(Transaction::PARENT_TYPE_BUDGET, $this->budgetId)
			->date('>=', $beginDate)
			->date('<=', $endDate);

		$transactions = $this->transactionOneShotSearch->get();

		return $transactions->map(function(Transaction $transaction) {
			/**
			 * @var TransactionPeriodicityOneShot $transactionPeriodicity
			 */
			$transactionPeriodicity = $transaction->periodicity;

			return new ScheduledTransaction(null, $transaction, $transactionPeriodicity->date);
		});
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

	/**
	 * Returns whatever date is earlier: now or given month begin.
	 * @return Carbon
	 */
	protected function getBeginDate(): Carbon {
		$beginDate = Date::stripTime(Carbon::now());

		if ($beginDate->lte($this->monthRange->getRangeBegin())) {
			return $beginDate;
		} else {
			return $this->monthRange->getRangeBegin();
		}
	}

	/**
	 * Returns whatever date is earlier: now or given month end.
	 * @return Carbon
	 */
	protected function getEndDate(): Carbon {
		$endDate = Date::stripTime(Carbon::now());

		if ($endDate->lte($this->monthRange->getRangeEnd())) {
			return $endDate;
		} else {
			return $this->monthRange->getRangeEnd();
		}
	}

}