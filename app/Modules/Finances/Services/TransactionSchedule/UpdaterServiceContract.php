<?php

namespace App\Modules\Finances\Services\TransactionSchedule;

interface UpdaterServiceContract {

	/**
	 * Rebuilds yearly schedule of given transaction.
	 * @param int $transactionId
	 * @return UpdaterServiceContract
	 */
	public function updateScheduleByTransactionId(int $transactionId): UpdaterServiceContract;

}