<?php

namespace App\Modules\Finances\Services\Transaction;

use App\ServiceContracts\BasicSearchContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Each transaction can have different periodicity - this interface describes a component that can parse the
 * transaction's periodicity and return dates (in future or past) when it should be/should have been applied.
 * ----
 * It requires setting the transaction's id and date range and returns a list of dates when selected transaction will be
 * accounted/booked/whatever.
 */
interface PeriodicityParserServiceContract
	extends BasicSearchContract {

	/**
	 * @param int $transactionId
	 * @return PeriodicityParserServiceContract
	 */
	public function setTransactionId(int $transactionId): self;

	/**
	 * Sets the beginning date from which dates should be calculated.
	 * @param Carbon $dateFrom
	 * @param Carbon $dateTo
	 * @return PeriodicityParserServiceContract
	 */
	public function setDateRange(Carbon $dateFrom, Carbon $dateTo): self;

	/**
	 * @return Collection|Carbon[]
	 */
	public function getRows(): Collection;

}