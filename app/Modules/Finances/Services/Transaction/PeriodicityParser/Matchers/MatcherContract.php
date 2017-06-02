<?php

namespace App\Modules\Finances\Services\Transaction\PeriodicityParser\Matchers;

use App\Modules\Finances\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Each transaction periodicity has to be compared differently - and thus the validation and matching logic is
 * divided into separate classes, all of which must follow this contract.
 */
interface MatcherContract {

	/**
	 * @param Transaction $transaction
	 * @return MatcherContract
	 */
	public function loadTransaction(Transaction $transaction): MatcherContract;

	/**
	 * @param Carbon $dateFrom
	 * @param Carbon $dateTo
	 * @return MatcherContract
	 */
	public function filterRange(Carbon $dateFrom, Carbon $dateTo): MatcherContract;

	/**
	 * @return Collection
	 */
	public function getMatchingDates(): Collection;

}