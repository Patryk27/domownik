<?php

namespace App\Console\Commands\Finances;

use App\Modules\Finances\Services\TransactionSchedule\ProcessorServiceContract;
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
	 * @var ProcessorServiceContract
	 */
	protected $transactionScheduleProcessorService;

	/**
	 * ProcessTransactionSchedule constructor.
	 * @param ProcessorServiceContract $transactionScheduleProcessorService
	 */
	public function __construct(
		ProcessorServiceContract $transactionScheduleProcessorService
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