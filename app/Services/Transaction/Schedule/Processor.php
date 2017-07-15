<?php

namespace App\Services\Transaction\Schedule;

use App\Models\Transaction;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use App\ValueObjects\ScheduledTransaction;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;

class Processor
	implements ProcessorContract {

	/**
	 * @var LoggerContract
	 */
	protected $log;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var TransactionScheduleRepositoryContract
	 */
	protected $transactionScheduleRepository;

	/**
	 * @var TransactionRepositoryContract
	 */
	protected $transactionRepository;

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

	/**
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	) {
		$this->log = $log;
		$this->db = $db;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
		$this->transactionRepository = $transactionRepository;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function processTransactionsSchedule(): ProcessorContract {
		try {
			$this->log->info('Processing transaction schedule...');
			$scheduledTransactions = $this->transactionScheduleRepository->getToDate(Carbon::yesterday());
			$this->log->info('Got %d scheduled transactions.', $scheduledTransactions->count());

			foreach ($scheduledTransactions as $scheduledTransaction) {
				$this->processScheduledTransaction($scheduledTransaction);
			}
		} finally {
			$this->log->info('Cleaning up...');

			$this->transactionRepository
				->getFlushCache()
				->flush();

			$this->transactionScheduleRepository
				->getFlushCache()
				->flush();
		}

		return $this;
	}

	/**
	 * Processes a single scheduled transaction.
	 * @param ScheduledTransaction $scheduledTransaction
	 * @return $this|Processor
	 */
	protected function processScheduledTransaction(ScheduledTransaction $scheduledTransaction): self {
		$stId = $scheduledTransaction->getId();
		$stDate = $scheduledTransaction->getDate();
		$stTransaction = $scheduledTransaction->getTransaction();

		$this->log->info('Processing scheduled transaction: id=%d, date=%s, transaction-id=%d.', $stId, $stDate->format('Y-m-d'), $stTransaction->id);

		$this->db->beginTransaction();

		try {
			// @todo consider using $stTransaction->replace() (without children!)
			$targetTransaction = new Transaction();
			$targetTransaction->parent_transaction_id = $stTransaction->id;
			$targetTransaction->parent_id = $stTransaction->parent_id;
			$targetTransaction->parent_type = $stTransaction->parent_type;
			$targetTransaction->category_id = $stTransaction->category_id;
			$targetTransaction->type = $stTransaction->type;
			$targetTransaction->name = $stTransaction->name;
			$targetTransaction->description = $stTransaction->description;

			// prepare transaction value
			/**
			 * @var TransactionValueConstant|TransactionValueRange $targetTransactionValue
			 */
			$targetTransactionValue = $stTransaction->value->replicate();
			$targetTransactionValue->saveOrFail();

			$targetTransactionValue
				->transaction()
				->save($targetTransaction);

			// prepare transaction periodicity
			$targetTransaction
				->periodicityOneShots()
				->create([
					'date' => $stDate,
				]);

			// save data and delete transaction from schedule
			$targetTransaction->saveOrFail();
			$this->transactionScheduleRepository->delete($stId);

			$this->log->info('-> created transaction: transaction-id=%d.', $targetTransaction->id);

			$this->db->commit();
		} catch (\Throwable $ex) {
			$this->log->error('Got an exception, aborting...');
			$this->db->rollBack();

			throw $ex;
		}

		return $this;
	}

}