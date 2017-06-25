<?php

namespace App\Modules\Finances\Services\TransactionSchedule;

interface ScheduleProcessorContract {

	/**
	 * Processes the transactions' schedules, booking appropriate transactions etc.
	 * @return ScheduleProcessorContract
	 */
	public function processTransactionsSchedule(): ScheduleProcessorContract;

}