<?php

namespace App\Services\Transaction;

use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Transaction\Crud\Request as TransactionCrudRequest;
use App\Http\Requests\Transaction\Crud\StoreRequest as TransactionStoreRequest;
use App\Http\Requests\Transaction\Crud\UpdateRequest as TransactionUpdateRequest;
use App\Models\Transaction;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Services\Transaction\Schedule\UpdaterContract as TransactionScheduleUpdaterContract;
use App\ValueObjects\Requests\Transaction\StoreResult as TransactionStoreResult;
use App\ValueObjects\Requests\Transaction\UpdateResult as TransactionUpdateResult;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;

class RequestProcessor
	implements RequestProcessorContract {

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
	 * @var TransactionScheduleUpdaterContract
	 */
	protected $transactionScheduleUpdater;

	/**
	 * @param DatabaseConnection $db
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 * @param TransactionScheduleUpdaterContract $transactionScheduleUpdater
	 */
	public function __construct(
		DatabaseConnection $db,
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository,
		TransactionScheduleUpdaterContract $transactionScheduleUpdater
	) {
		$this->db = $db;
		$this->transactionRepository = $transactionRepository;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
		$this->transactionScheduleUpdater = $transactionScheduleUpdater;
	}

	/**
	 * @inheritdoc
	 */
	public function store(TransactionStoreRequest $request): TransactionStoreResult {
		return $this->db->transaction(function () use ($request) {
			$transaction = new Transaction([
				'parent_id' => $request->get('parent_id'),
				'parent_type' => $request->get('parent_type'),
			]);

			$this->fillTransaction($transaction, $request);

			$this->transactionRepository->persist($transaction);
			$this->transactionScheduleUpdater->updateTransactionSchedule($transaction->id);

			return new TransactionStoreResult($transaction);
		});
	}

	/**
	 * @inheritdoc
	 */
	public function update(TransactionUpdateRequest $request, int $id): TransactionUpdateResult {
		return $this->db->transaction(function () use ($request, $id) {
			$transaction = $this->transactionRepository->getOrFail($id);
			$this->fillTransaction($transaction, $request);

			$this->transactionRepository->persist($transaction);
			$this->transactionScheduleUpdater->updateTransactionSchedule($transaction->id);

			return new TransactionUpdateResult($transaction);
		});
	}

	/**
	 * @inheritdoc
	 */
	public function delete(int $id): void {
		$this->transactionRepository->delete($id);
	}

	/**
	 * @param Transaction $transaction
	 * @param TransactionCrudRequest $request
	 * @return $this
	 */
	protected function fillTransaction(Transaction $transaction, TransactionCrudRequest $request) {
		$this
			->fillTransactionMeta($transaction, $request)
			->fillTransactionValue($transaction, $request)
			->fillTransactionPeriodicity($transaction, $request);

		return $this;
	}

	/**
	 * @param Transaction $transaction
	 * @param TransactionCrudRequest $request
	 * @return $this
	 */
	protected function fillTransactionMeta(Transaction $transaction, TransactionCrudRequest $request) {
		$transaction->fill([
			'type' => $request->get('type'),
			'name' => $request->get('name'),
			'category_id' => $request->get('category_id'),
			'description' => $request->get('description'),
			'value_type' => $request->get('value_type'),
			'periodicity_type' => $request->get('periodicity_type'),
		]);

		return $this;
	}

	/**
	 * @param Transaction $transaction
	 * @param TransactionCrudRequest $request
	 * @return $this
	 * @throws InvalidRequestException
	 */
	protected function fillTransactionValue(Transaction $transaction, TransactionCrudRequest $request) {
		switch ($request->get('value_type')) {
			case Transaction::VALUE_TYPE_CONSTANT:
				$transactionValue = new TransactionValueConstant([
					'value' => $request->get('value_constant_value'),
				]);

				$transactionValue->saveOrFail();

				$transactionValue
					->transaction()
					->save($transaction);

				break;

			case Transaction::VALUE_TYPE_RANGE:
				$transactionValue = new TransactionValueRange([
					'value_from' => $request->get('value_range_from'),
					'value_to' => $request->get('value_range_to'),
				]);

				$transactionValue->saveOrFail();

				$transactionValue
					->transaction()
					->save($transaction);

				break;

			default:
				throw new InvalidRequestException('Unexpected transaction value type [%s].', $request->get('value_type'));
		}

		return $this;
	}

	/**
	 * @param Transaction $transaction
	 * @param TransactionCrudRequest $request
	 * @return $this
	 * @throws InvalidRequestException
	 */
	protected function fillTransactionPeriodicity(Transaction $transaction, TransactionCrudRequest $request) {
		if ($transaction->exists) {
			$this->transactionPeriodicityRepository->deleteByTransactionId($transaction->id);
		}

		switch ($request->get('periodicity_type')) {
			case Transaction::PERIODICITY_TYPE_ONE_SHOT:
				foreach ($request->get('calendar_dates') as $date) {
					$transaction
						->periodicityOneShots()
						->create([
							'date' => $date,
						]);
				}

				break;

			case Transaction::PERIODICITY_TYPE_DAILY:
				// nottin' here, left for validation purposes only
				break;

			case Transaction::PERIODICITY_TYPE_WEEKLY:
				foreach ($request->get('periodicity_weekly_days') as $weekday) {
					$transaction
						->periodicityWeeklies()
						->create([
							'weekday' => $weekday,
						]);
				}

				break;

			case Transaction::PERIODICITY_TYPE_MONTHLY:
				foreach ($request->get('periodicity_monthly_days') as $day) {
					$transaction
						->periodicityMonthlies()
						->create([
							'day' => $day,
						]);
				}

				break;

			case Transaction::PERIODICITY_TYPE_YEARLY:
				foreach ($request->get('calendar_dates') as $date) {
					$date = new Carbon($date);

					$transaction
						->periodicityYearlies()
						->create([
							'month' => $date->month,
							'day' => $date->day,
						]);
				}

				break;

			default:
				throw new InvalidRequestException('Unexpected transaction periodicity type [%s].', $request->get('periodicity_type'));
		}

		return $this;
	}

}