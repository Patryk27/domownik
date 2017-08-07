<?php

namespace App\Services\Transaction\Periodicity;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Each transaction can have different periodicity - this interface describes a component that can parse the
 * transaction's periodicity and return dates (in future or past) when it should be/should have been applied.
 * ----
 * It requires setting the transaction's id and date range and returns a list of dates when selected transaction will be
 * accounted/booked/whatever.
 */
interface ParserContract {

	/**
	 * @return $this
	 */
	public function reset();

	/**
	 * @param int $transactionId
	 * @return $this
	 */
	public function setTransactionId(int $transactionId);

	/**
	 * Sets the beginning date from which dates should be calculated.
	 * @param Carbon $dateFrom
	 * @param Carbon $dateTo
	 * @return $this
	 */
	public function setDateRange(Carbon $dateFrom, Carbon $dateTo);

	/**
	 * @return Collection|Carbon[]
	 */
	public function getRows(): Collection;

}