<?php

namespace App\Services\Transaction\PeriodicityParser\Matchers;

use App\Models\Transaction;
use App\Models\TransactionPeriodicityYearly;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class YearlyMatcher
	implements MatcherContract {

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

	/**
	 * @var Collection|string[]
	 */
	protected $yearDays;

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
		$rows = $this->transactionPeriodicityRepository->getYearliesByTransactionId($transaction->id);

		$this->yearDays = $rows->map(function(TransactionPeriodicityYearly $row) {
			return sprintf('%02d-%02d', $row->month, $row->day);
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
			$yearDay = sprintf('%02d-%02d', $currentDay->month, $currentDay->day);

			if ($this->yearDays->contains($yearDay)) {
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