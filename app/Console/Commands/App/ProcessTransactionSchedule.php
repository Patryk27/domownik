<?php

namespace App\Console\Commands\App;

use App\Services\Transaction\Schedule\ProcessorContract as TransactionScheduleProcessorContract;
use Illuminate\Console\Command;

class ProcessTransactionSchedule
	extends Command {

	/**
	 * @var string
	 */
	protected $signature = 'app:process-transaction-schedule';

	/**
	 * @var string
	 */
	protected $description = 'Processes the transaction schedule, booking appropriate transactions (if any).';

	/**
	 * @var TransactionScheduleProcessorContract
	 */
	protected $transactionScheduleProcessor;

	/**
	 * @param TransactionScheduleProcessorContract $transactionScheduleProcessor
	 */
	public function __construct(
		TransactionScheduleProcessorContract $transactionScheduleProcessor
	) {
		parent::__construct();

		$this->transactionScheduleProcessor = $transactionScheduleProcessor;
	}

	/**
	 * @return void
	 */
	public function handle(): void {
		$this->info('Processing transaction schedule...');
		$result = $this->transactionScheduleProcessor->processSchedule();
		$this->info(sprintf('Found and processed %d transactions.', $result->getProcessedTransactionCount()));
	}

}