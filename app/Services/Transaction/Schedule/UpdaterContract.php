<?php

namespace App\Services\Transaction\Schedule;

interface UpdaterContract {

	/**
	 * Rebuilds yearly schedule of given transaction.
	 * @param int $transactionId
	 * @return UpdaterContract
	 */
	public function updateTransactionSchedule(int $transactionId): UpdaterContract;

}