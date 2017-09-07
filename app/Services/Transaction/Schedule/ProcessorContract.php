<?php

namespace App\Services\Transaction\Schedule;

use App\ValueObjects\Transaction\Schedule\Processor\Result as TransactionScheduleProcessorResult;

interface ProcessorContract {

	/**
	 * Processes whole transaction schedule.
	 * By the default, this method is called once a day.
	 * @return TransactionScheduleProcessorResult
	 */
	public function processSchedule(): TransactionScheduleProcessorResult;

}