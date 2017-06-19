<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Exceptions\InvalidRequestException;
use App\Modules\Finances\Http\Requests\Transaction\StoreRequest;

use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionSchedule;
use App\Modules\Finances\Models\TransactionValueConstant;
use App\Modules\Finances\Models\TransactionValueRange;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Support\Facades\MyLog;
use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;

class RequestManagerService
	implements RequestManagerServiceContract {

	/**
	 * @var Connection
	 */
	protected $databaseConnection;

	/**
	 * @var TransactionRepositoryContract
	 */
	protected $transactionRepository;

	/**
	 * @var StoreRequest
	 */
	protected $request;

	/**
	 * @var Transaction
	 */
	protected $transaction;

	/**
	 * @var bool
	 */
	protected $beingCreated;

	/**
	 * CreateEditService constructor.
	 * @param Connection $databaseConnection
	 * @param TransactionRepositoryContract $transactionRepository
	 */
	public function __construct(
		Connection $databaseConnection,
		TransactionRepositoryContract $transactionRepository
	) {
		$this->databaseConnection = $databaseConnection;
		$this->transactionRepository = $transactionRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function store(StoreRequest $request): RequestManagerServiceContract {
		$this->request = $request;
		$this->beingCreated = !$request->has('transactionId');

		if ($this->beingCreated) {
			MyLog::info('Storing new transaction: %s', $request);
		} else {
			MyLog::info('Updating existing transaction #%d: %s', $this->request->get('transactionId'), $request);
		}

		$this->databaseConnection->transaction(function() {
			if ($this->beingCreated) {
				$this->createTransaction();
			} else {
				$this->loadTransaction();
			}

			$this
				->persistTransactionMeta()
				->persistTransactionValue()
				->persistTransactionPeriodicity();

			$this->transaction->saveOrFail();

			$this->transactionRepository
				->getFlushCache()
				->flush();
		});

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function delete(int $transactionId): RequestManagerServiceContract {
		MyLog::info('Deleting transaction: id=%d', $transactionId);

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
	public function getTransaction(): Transaction {
		return $this->transaction;
	}

	/**
	 * @inheritDoc
	 */
	public function isNew(): bool {
		return $this->beingCreated;
	}

	/**
	 * @return $this
	 */
	protected function createTransaction() {
		$this->transaction = new Transaction();
		$this->transaction->parent_id = $this->request->get('transactionParentId');
		$this->transaction->parent_type = $this->request->get('transactionParentType');

		return $this;
	}

	/**
	 * @throws InvalidRequestException
	 * @return $this
	 */
	protected function loadTransaction() {
		$transactionId = $this->request->get('transactionId');
		$this->transaction = $this->transactionRepository->getOrFail($transactionId);

		/**
		 * Right below we're basically pruning the transaction of all its data,
		 * just because it's easier for us to remove everything and insert
		 * again* than compare what's to add and what's to update.
		 * ----
		 * * right here we can do it because value's and periodicities' ids does
		 * not matter anywhere.
		 */

		$this->transaction->value->delete();

		/**
		 * @var Model[] $periodicityModels
		 */
		$periodicityModels = [];

		switch ($this->transaction->periodicity_type) {
			case Transaction::PERIODICITY_TYPE_ONE_SHOT:
				$periodicityModels = $this->transaction->periodicityOneShots;
				break;

			case Transaction::PERIODICITY_TYPE_DAILY:
				$periodicityModels = $this->transaction->periodicityDailes;
				break;

			case Transaction::PERIODICITY_TYPE_WEEKLY:
				$periodicityModels = $this->transaction->periodicityWeeklies;
				break;

			case Transaction::PERIODICITY_TYPE_MONTHLY:
				$periodicityModels = $this->transaction->periodicityMonthlies;
				break;

			case Transaction::PERIODICITY_TYPE_YEARLY:
				$periodicityModels = $this->transaction->periodicityYearlies;
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
		$this->transaction->type = $this->request->get('transactionType');
		$this->transaction->name = $this->request->get('transactionName');
		$this->transaction->category_id = $this->request->get('transactionCategoryId');
		$this->transaction->description = $this->request->get('transactionDescription');
		$this->transaction->value_type = $this->request->get('transactionValueType');
		$this->transaction->periodicity_type = $this->request->get('transactionPeriodicityType');

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
					->save($this->transaction);

				break;

			case Transaction::VALUE_TYPE_RANGE:
				$transactionValue = new TransactionValueRange();
				$transactionValue->value_from = $this->request->get('transactionValueRangeFrom');
				$transactionValue->value_to = $this->request->get('transactionValueRangeTo');
				$transactionValue->saveOrfail();

				$transactionValue
					->transaction()
					->save($this->transaction);

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
		$this->transaction->periodicity_type = $this->request->get('transactionPeriodicityType');

		switch ($this->transaction->periodicity_type) {
			case Transaction::PERIODICITY_TYPE_ONE_SHOT:
				foreach ($this->request->get('calendarDates') as $calendarDate) {
					$this->transaction
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
				foreach ($this->request->get('transactionPeriodicityWeeklyDays') as $weekDayNumber) {
					$this->transaction
						->periodicityWeeklies()
						->create([
							'weekday_number' => $weekDayNumber,
						]);
				}

				break;

			case Transaction::PERIODICITY_TYPE_MONTHLY:
				foreach ($this->request->get('transactionPeriodicityMonthlyDays') as $dayNumber) {
					$this->transaction
						->periodicityMonthlies()
						->create([
							'day_number' => $dayNumber,
						]);
				}

				break;

			case Transaction::PERIODICITY_TYPE_YEARLY:
				foreach ($this->request->get('calendarDates') as $calendarDate) {
					$date = new Carbon($calendarDate);

					$this->transaction
						->periodicityYearlies()
						->create([
							'month' => $date->month,
							'day' => $date->day,
						]);
				}

				break;

			default:
				throw new InvalidRequestException('Unexpected transaction periodicity type: %s.', $this->transaction->periodicity_type);
		}

		return $this;
	}

}