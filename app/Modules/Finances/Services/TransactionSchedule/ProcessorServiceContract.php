<?php

namespace App\Modules\Finances\Services\TransactionSchedule;

interface ProcessorServiceContract {

	/**
	 * Processes the transactions' schedules, booking appropriate transactions etc.
	 * @return ProcessorServiceContract
	 */
	public function processTransactionsSchedule(): ProcessorServiceContract;

}