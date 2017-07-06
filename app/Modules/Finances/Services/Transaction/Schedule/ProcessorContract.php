<?php

namespace App\Modules\Finances\Services\Transaction\Schedule;

interface ProcessorContract {

	/**
	 * Processes the transactions' schedules, booking appropriate transactions etc.
	 * @return ProcessorContract
	 */
	public function processTransactionsSchedule(): ProcessorContract;

}