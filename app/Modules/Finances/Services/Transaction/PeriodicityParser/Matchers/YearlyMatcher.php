<?php

namespace App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers;

use App\Modules\Finances\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class YearlyMatcher
	implements MatcherContract {

	/**
	 * @var array
	 */
	protected $yearDays;

	/**
	 * @var Carbon[]
	 */
	protected $dates;

	/**
	 * @inheritDoc
	 */
	public function loadTransaction(Transaction $transaction): MatcherContract {
		$rows =
			$transaction
				->periodicityYearlies()
				->get(['month', 'day']);

		$this->yearDays = [];

		foreach ($rows as $row) {
			$this->yearDays[] = sprintf('%02d-%02d', $row->month, $row->day);
		}

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function filterRange(Carbon $dateFrom, Carbon $dateTo): MatcherContract {
		$this->dates = [];

		$currentDay = $dateFrom->copy();

		while ($currentDay <= $dateTo) {
			$currentDayString = sprintf('%02d-%02d', $currentDay->month, $currentDay->day);

			if (in_array($currentDayString, $this->yearDays, true)) {
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