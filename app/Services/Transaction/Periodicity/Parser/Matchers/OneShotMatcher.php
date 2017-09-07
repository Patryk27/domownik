<?php

namespace App\Services\Transaction\Periodicity\Parser\Matchers;

use App\Models\Transaction;
use App\Models\TransactionPeriodicityOneShot;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OneShotMatcher
	implements MatcherContract {

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

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
		$rows = $this->transactionPeriodicityRepository->getOneShotsByTransactionId($transaction->id);

		$this->dates = $rows->map(function (TransactionPeriodicityOneShot $row) {
			return $row->date;
		});

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function filterRange(Carbon $dateFrom, Carbon $dateTo): MatcherContract {
		$filteredDates = [];

		foreach ($this->dates as $date) {
			if ($date >= $dateFrom && $date <= $dateTo) {
				$filteredDates[] = $date;
			}
		}

		$this->dates = $filteredDates;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getMatchingDates(): Collection {
		return new Collection($this->dates);
	}

}