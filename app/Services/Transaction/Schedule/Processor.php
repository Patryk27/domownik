<?php

namespace App\Services\Transaction\Schedule;

use App\Models\Transaction;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use App\Services\Search\Transaction\ScheduleSearchContract as TransactionScheduleSearchContract;
use App\ValueObjects\ScheduledTransaction;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Support\Collection;
use Throwable;

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
	 * @var TransactionRepositoryContract
	 */
	protected $transactionRepository;

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

	/**
	 * @var TransactionScheduleRepositoryContract
	 */
	protected $transactionScheduleRepository;

	/**
	 * @var TransactionScheduleSearchContract
	 */
	protected $transactionScheduleSearch;

	/**
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param TransactionScheduleSearchContract $transactionScheduleSearch
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		TransactionScheduleSearchContract $transactionScheduleSearch
	) {
		$this->log = $log;
		$this->db = $db;
		$this->transactionRepository = $transactionRepository;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
		$this->transactionScheduleSearch = $transactionScheduleSearch;
	}

	/**
	 * @inheritDoc
	 */
	public function processSchedule(): ProcessorContract {
		try {
			$this->log->info('Fetching scheduled transactions...');
			$scheduledTransactions = $this->getScheduledTransactions();
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
	 * @return Collection|ScheduledTransaction[]
	 */
	protected function getScheduledTransactions(): Collection {
		return
			$this->transactionScheduleSearch
				->date('<=', Carbon::yesterday())
				->get();
	}

	/**
	 * Processes a single scheduled transaction.
	 * @param ScheduledTransaction $scheduledTransaction
	 * @return Processor
	 * @throws Throwable
	 */
	protected function processScheduledTransaction(ScheduledTransaction $scheduledTransaction) {
		$stId = $scheduledTransaction->getId();
		$stDate = $scheduledTransaction->getDate();
		$stTransaction = $scheduledTransaction->getTransaction();

		$this->log->info('Processing scheduled transaction: id=%d, date=%s, transaction-id=%d.', $stId, $stDate->format('Y-m-d'), $stTransaction->id);

		$this->db->beginTransaction();

		try {
			$newTransaction = new Transaction([
				'parent_transaction_id' => $stTransaction->id,
				'parent_id' => $stTransaction->parent_id,
				'parent_type' => $stTransaction->parent_type,
				'category_id' => $stTransaction->category_id,
				'type' => $stTransaction->type,
				'name' => $stTransaction->name,
				'description' => $stTransaction->description,
				'periodicity_type' => Transaction::PERIODICITY_TYPE_ONE_SHOT,
			]);

			// prepare transaction value
			/**
			 * @var TransactionValueConstant|TransactionValueRange $newTransactionValue
			 */
			$newTransactionValue = $stTransaction->value->replicate();
			$newTransactionValue->saveOrFail();

			$newTransactionValue
				->transaction()
				->save($newTransaction);

			// prepare transaction periodicity
			$newTransaction
				->periodicityOneShots()
				->create([
					'date' => $stDate,
				]);

			// save data and delete transaction from schedule
			$this->transactionRepository->persist($newTransaction);
			$this->transactionScheduleRepository->delete($stId);

			$this->log->info('-> created transaction: transaction-id=%d.', $newTransaction->id);

			$this->db->commit();
		} catch (Throwable $ex) {
			$this->log->error('Got an exception, aborting...');
			$this->db->rollBack();

			throw $ex;
		}

		return $this;
	}

}