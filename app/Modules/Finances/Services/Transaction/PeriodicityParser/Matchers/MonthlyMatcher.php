<?php

namespace App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers;

use App\Modules\Finances\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MonthlyMatcher
	implements MatcherContract {

	/**
	 * @var int[]
	 */
	protected $dayNumbers;

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
				->periodicityMonthlies()
				->get(['day_number']);

		$this->dayNumbers = [];

		foreach ($rows as $row) {
			$this->dayNumbers[] = $row->day_number;
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
			if (in_array($currentDay->day, $this->dayNumbers, true)) {
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