<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Exceptions\InvalidRequestException;
use App\Modules\Finances\Http\Requests\Transaction\StoreRequest as TransactionStoreRequest;
use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionValueConstant;
use App\Modules\Finances\Models\TransactionValueRange;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;

class RequestManager
	implements RequestManagerContract {

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
	 * @var TransactionStoreRequest
	 */
	protected $request;

	/**
	 * @var Transaction
	 */
	protected $model;

	/**
	 * @var bool
	 */
	protected $beingCreated;

	/**
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository,
		TransactionScheduleRepositoryContract $transactionScheduleRepository
	) {
		$this->log = $log;
		$this->db = $db;
		$this->transactionRepository = $transactionRepository;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function store(TransactionStoreRequest $request): string {
		$this->request = $request;
		$this->beingCreated = !$request->has('transactionId');

		if ($this->beingCreated) {
			$this->log->info('Creating new transaction: %s', $request);
		} else {
			$this->log->info('Updating transaction with id=%d: %s', $this->request->get('transactionId'), $request);
		}

		$this->db->transaction(function() {
			if ($this->beingCreated) {
				$this->createTransaction();
			} else {
				$this->loadTransaction();
			}

			$this
				->persistTransactionMeta()
				->persistTransactionValue()
				->persistTransactionPeriodicity();

			$this->model->saveOrFail();

			$this->transactionRepository
				->getFlushCache()
				->flush();
		});

		return $this->beingCreated ? self::STORE_RESULT_CREATED : self::STORE_RESULT_UPDATED;
	}

	/**
	 * @inheritDoc
	 */
	public function delete(int $transactionId): RequestManagerContract {
		$this->log->info('Deleting transaction: id=%d', $transactionId);

		// checks if transaction exists
		$transaction = $this->transactionRepository->getOrFail($transactionId);

		// delete transaction data
		$this->transactionPeriodicityRepository->deleteByTransactionId($transaction->id);
		$this->transactionScheduleRepository->deleteByTransactionId($transaction->id);

		$this->transactionRepository->delete($transaction->id);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getModel(): Transaction {
		return $this->model;
	}

	/**
	 * @return $this
	 */
	protected function createTransaction() {
		$this->model = new Transaction();
		$this->model->parent_id = $this->request->get('transactionParentId');
		$this->model->parent_type = $this->request->get('transactionParentType');

		return $this;
	}

	/**
	 * @throws InvalidRequestException
	 * @return $this
	 */
	protected function loadTransaction() {
		$transactionId = $this->request->get('transactionId');
		$this->model = $this->transactionRepository->getOrFail($transactionId);

		/**
		 * Instead of trying to find what has changed between currently saved transaction and the new version, it is
		 * easier to just drop the whole transaction's value and periodicity/-ies and insert them again.
		 * -----
		 * One can rely on a transaction's id but not on eg. transaction value's id (and that is done by design) -
		 * that's why it's safe to do this that way.
		 */

		return $this;
	}

	/**
	 * @return $this
	 */
	protected function persistTransactionMeta() {
		$this->model->type = $this->request->get('transactionType');
		$this->model->name = $this->request->get('transactionName');
		$this->model->category_id = $this->request->get('transactionCategoryId');
		$this->model->description = $this->request->get('transactionDescription');
		$this->model->value_type = $this->request->get('transactionValueType');
		$this->model->periodicity_type = $this->request->get('transactionPeriodicityType');

		return $this;
	}

	/**
	 * @return $this
	 * @throws InvalidRequestException
	 */
	protected function persistTransactionValue() {
		switch ($this->request->get('transactionValueType')) {
			case Transaction::VALUE_TYPE_CONSTANT:
				$transactionValue = new TransactionValueConstant();
				$transactionValue->value = $this->request->get('transactionValueConstantValue');
				$transactionValue->saveOrFail();

				$transactionValue
					->transaction()
					->save($this->model);

				break;

			case Transaction::VALUE_TYPE_RANGE:
				$transactionValue = new TransactionValueRange();
				$transactionValue->value_from = $this->request->get('transactionValueRangeFrom');
				$transactionValue->value_to = $this->request->get('transactionValueRangeTo');
				$transactionValue->saveOrfail();

				$transactionValue
					->transaction()
					->save($this->model);

				break;

			default:
				throw new InvalidRequestException('Unexpected transaction value type: %s.', $this->request->get('transactionValueType'));
		}

		return $this;
	}

	/**
	 * @return $this
	 * @throws InvalidRequestException
	 */
	protected function persistTransactionPeriodicity() {
		if ($this->model->exists) {
			$this->transactionPeriodicityRepository->deleteByTransactionId($this->model->id);
		}

		$this->model->periodicity_type = $this->request->get('transactionPeriodicityType');

		switch ($this->model->periodicity_type) {
			case Transaction::PERIODICITY_TYPE_ONE_SHOT:
				foreach ($this->request->get('calendarDates') as $calendarDate) {
					$this->model
						->periodicityOneShots()
						->create([
							'date' => $calendarDate,
						]);
				}

				break;

			case Transaction::PERIODICITY_TYPE_DAILY:
				// nottin' here, left for validation purposes only
				break;

			case Transaction::PERIODICITY_TYPE_WEEKLY:
				foreach ($this->request->get('transactionPeriodicityWeeklyDays') as $weekday) {
					$this->model
						->periodicityWeeklies()
						->create([
							'weekday' => $weekday,
						]);
				}

				break;

			case Transaction::PERIODICITY_TYPE_MONTHLY:
				foreach ($this->request->get('transactionPeriodicityMonthlyDays') as $day) {
					$this->model
						->periodicityMonthlies()
						->create([
							'day' => $day,
						]);
				}

				break;

			case Transaction::PERIODICITY_TYPE_YEARLY:
				foreach ($this->request->get('calendarDates') as $calendarDate) {
					$date = new Carbon($calendarDate);

					$this->model
						->periodicityYearlies()
						->create([
							'month' => $date->month,
							'day' => $date->day,
						]);
				}

				break;

			default:
				throw new InvalidRequestException('Unexpected transaction periodicity type: %s.', $this->model->periodicity_type);
		}

		return $this;
	}

}