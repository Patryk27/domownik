<?php

namespace App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers;

use App\Modules\Finances\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OneShotMatcher
	implements MatcherContract {

	/**
	 * @var Carbon[]
	 */
	protected $dates;

	/**
	 * @inheritDoc
	 */
	public function loadTransaction(Transaction $transaction): MatcherContract {
		$oneShots =
			$transaction
				->periodicityOneShots()
				->get(['date']);

		$this->dates = [];

		foreach ($oneShots as $oneShot) {
			$this->dates[] = new Carbon($oneShot->date);
		}

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