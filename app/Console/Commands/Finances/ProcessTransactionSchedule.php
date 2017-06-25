<?php

namespace App\Console\Commands\Finances;

use App\Modules\Finances\Services\TransactionSchedule\ScheduleProcessorContract;
use Illuminate\Console\Command;

class ProcessTransactionSchedule
	extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'dk:finances:process-transaction-schedule';

	/**
	 * @var string
	 */
	protected $description = 'Processes the transaction schedule, booking appropriate transactions (if any).';

	/**
	 * @var ScheduleProcessorContract
	 */
	protected $transactionScheduleProcessorService;

	/**
	 * @param ScheduleProcessorContract $transactionScheduleProcessorService
	 */
	public function __construct(
		ScheduleProcessorContract $transactionScheduleProcessorService
	) {
		parent::__construct();
		$this->transactionScheduleProcessorService = $transactionScheduleProcessorService;
	}

	/**
	 * @return void
	 */
	public function handle() {
		$this->transactionScheduleProcessorService->processTransactionsSchedule();
	}

}