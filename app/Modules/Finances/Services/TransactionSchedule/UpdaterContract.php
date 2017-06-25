<?php

namespace App\Modules\Finances\Services\TransactionSchedule;

interface UpdaterContract {

	/**
	 * Rebuilds yearly schedule of given transaction.
	 * @param int $transactionId
	 * @return UpdaterContract
	 */
	public function updateScheduleByTransactionId(int $transactionId): UpdaterContract;

}