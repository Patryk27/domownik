<?php

namespace App\Services\Transaction\PeriodicityParser\Matchers;

use App\Models\Transaction;
use App\Models\TransactionPeriodicityWeekly;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Support\Facades\Calendar;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WeeklyMatcher
	implements MatcherContract {

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

	/**
	 * @var Collection|int[]
	 */
	protected $weekDayNumbers;

	/**
	 * @var Carbon[]
	 */
	public $dates;

	/**
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 */
	public function __construct(
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	) {
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function loadTransaction(Transaction $transaction): MatcherContract {
		$rows = $this->transactionPeriodicityRepository->getWeekliesByTransactionId($transaction->id);

		$this->weekDayNumbers = $rows->map(function(TransactionPeriodicityWeekly $row) {
			return $row->weekday;
		});

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function filterRange(Carbon $dateFrom, Carbon $dateTo): MatcherContract {
		$this->dates = [];

		$currentDay = $dateFrom->copy();

		while ($currentDay <= $dateTo) {
			$weekDayNumber = Calendar::getCarbonWeekdaysMapping()[$currentDay->dayOfWeek];

			if ($this->weekDayNumbers->contains($weekDayNumber)) {
				$this->dates[] = $currentDay->copy();
			}

			$currentDay->addDay();
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getMatchingDates(): Collection {
		return new Collection($this->dates);
	}

}