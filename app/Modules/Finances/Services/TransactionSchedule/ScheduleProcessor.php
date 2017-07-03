<?php

namespace App\Modules\Finances\Services\TransactionSchedule;

use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionValueConstant;
use App\Modules\Finances\Models\TransactionValueRange;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Modules\Finances\ValueObjects\ScheduledTransaction;
use App\Services\Logger\Contract as LoggerContract;
use Carbon\Carbon;
use Illuminate\Database\Connection;

class ScheduleProcessor
	implements ScheduleProcessorContract {

	/**
	 * @var LoggerContract
	 */
	protected $logger;

	/**
	 * @var Connection
	 */
	protected $databaseConnection;

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
	 * @param LoggerContract $logger
	 * @param Connection $databaseConnection
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 */
	public function __construct(
		LoggerContract $logger,
		Connection $databaseConnection,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	) {
		$this->logger = $logger;
		$this->databaseConnection = $databaseConnection;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
		$this->transactionRepository = $transactionRepository;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function processTransactionsSchedule(): ScheduleProcessorContract {
		try {
			$this->logger->info('Processing transaction schedule...');
			$scheduledTransactions = $this->transactionScheduleRepository->getToDate(Carbon::yesterday());
			$this->logger->info('Got %d scheduled transactions.', $scheduledTransactions->count());

			foreach ($scheduledTransactions as $scheduledTransaction) {
				$this->processScheduledTransaction($scheduledTransaction);
			}
		} finally {
			$this->logger->info('Cleaning up...');

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
	 * @return $this|ScheduleProcessor
	 */
	protected function processScheduledTransaction(ScheduledTransaction $scheduledTransaction): self {
		$stId = $scheduledTransaction->getId();
		$stDate = $scheduledTransaction->getDate();
		$stTransaction = $scheduledTransaction->getTransaction();

		$this->logger->info('Processing scheduled transaction: id=%d, date=%s, transaction-id=%d.', $stId, $stDate->format('Y-m-d'), $stTransaction->id);

		$this->databaseConnection->beginTransaction();

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

			$this->logger->info('-> created transaction: transaction-id=%d.', $targetTransaction->id);

			$this->databaseConnection->commit();
		} catch (\Throwable $ex) {
			$this->logger->error('Got an exception, aborting...');
			$this->databaseConnection->rollBack();

			throw $ex;
		}

		return $this;
	}

}