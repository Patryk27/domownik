<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Exceptions\InvalidRequestException;
use App\Modules\Finances\Http\Requests\Transaction\StoreRequest as TransactionStoreRequest;
use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionSchedule;
use App\Modules\Finances\Models\TransactionValueConstant;
use App\Modules\Finances\Models\TransactionValueRange;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Eloquent\Model;

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
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		TransactionRepositoryContract $transactionRepository
	) {
		$this->log = $log;
		$this->db = $db;
		$this->transactionRepository = $transactionRepository;
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

		$this->transactionRepository->delete($transactionId);

		Transaction
			::getFlushCache()
			->flush();

		TransactionSchedule
			::getFlushCache()
			->flush();

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
		 * Right below we're basically pruning the transaction of all its data,
		 * just because it's easier for us to remove everything and insert
		 * again* than compare what's to add and what's to update.
		 * ----
		 * * right here we can do it because value's and periodicities' ids does
		 * not matter anywhere.
		 */

		$this->model->value->delete();

		/**
		 * @var Model[] $periodicityModels
		 */
		$periodicityModels = [];

		switch ($this->model->periodicity_type) {
			case Transaction::PERIODICITY_TYPE_ONE_SHOT:
				$periodicityModels = $this->model->periodicityOneShots;
				break;

			case Transaction::PERIODICITY_TYPE_DAILY:
				$periodicityModels = $this->model->periodicityDailes;
				break;

			case Transaction::PERIODICITY_TYPE_WEEKLY:
				$periodicityModels = $this->model->periodicityWeeklies;
				break;

			case Transaction::PERIODICITY_TYPE_MONTHLY:
				$periodicityModels = $this->model->periodicityMonthlies;
				break;

			case Transaction::PERIODICITY_TYPE_YEARLY:
				$periodicityModels = $this->model->periodicityYearlies;
				break;
		}

		if (!empty($periodicityModels)) {
			/**
			 * This is possibly the slowest but also the most readable solution.
			 * Thank god I'm not writing an RTOS.
			 */
			foreach ($periodicityModels as $periodicityModel) {
				$periodicityModel->delete();
			}
		}

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