<?php

namespace App\Console\Commands\Finances;

use App\Modules\Finances\Services\Transaction\Schedule\ProcessorContract;
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
	 * @var ProcessorContract
	 */
	protected $transactionScheduleProcessorService;

	/**
	 * @param ProcessorContract $transactionScheduleProcessorService
	 */
	public function __construct(
		ProcessorContract $transactionScheduleProcessorService
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