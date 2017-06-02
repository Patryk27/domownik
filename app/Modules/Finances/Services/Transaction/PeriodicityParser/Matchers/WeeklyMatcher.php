<?php

namespace App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers;

use App\Modules\Finances\Models\Transaction;
use App\Support\Facades\Calendar;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WeeklyMatcher
	implements MatcherContract {

	/**
	 * @var int[]
	 */
	protected $weekDayNumbers;

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
				->periodicityWeeklies()
				->get(['weekday_number']);

		$this->weekDayNumbers = [];

		foreach ($rows as $row) {
			$this->weekDayNumbers[] = $row->weekday_number;
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
			$isoDayOfWeek = Calendar::getCarbonWeekdaysMapping()[$currentDay->dayOfWeek];

			if (in_array($isoDayOfWeek, $this->weekDayNumbers, true)) {
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