<?php

namespace App\Modules\Finances\Services\TransactionSchedule;

use App\Modules\Finances\Models\Transaction;

use App\Modules\Finances\Models\TransactionValueConstant;
use App\Modules\Finances\Models\TransactionValueRange;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;

use App\Modules\Finances\ValueObjects\ScheduledTransaction;
use App\Support\Facades\MyLog;
use Carbon\Carbon;
use Illuminate\Database\Connection;

class ScheduleProcessor
	implements ScheduleProcessorContract {

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
	 * @param Connection $databaseConnection
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 */
	public function __construct(
		Connection $databaseConnection,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	) {
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
			// process transaction schedule
			MyLog::info('Processing transaction schedule...');

			$scheduledTransactions = $this->transactionScheduleRepository->getToDate(Carbon::yesterday());

			MyLog::info('Got %d scheduled transactions.', $scheduledTransactions->count());

			foreach ($scheduledTransactions as $scheduledTransaction) {
				$this->processScheduledTransaction($scheduledTransaction);
			}
		} finally {
			// flush caches
			MyLog::info('Cleaning up...');

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

		MyLog::info('Processing scheduled transaction: id=%d, date=%s, transaction-id=%d.', $stId, $stDate->format('Y-m-d'), $stTransaction->id);

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

			// save data
			$targetTransaction->saveOrFail();

			$this->transactionScheduleRepository->delete($stId);

			MyLog::info('-> created transaction: transaction-id=%d.', $targetTransaction->id);

			$this->databaseConnection->commit();
		} catch (\Throwable $ex) {
			MyLog::error('Got an exception, aborting...');
			$this->databaseConnection->rollBack();

			throw $ex;
		}

		return $this;
	}

}