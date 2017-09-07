<?php

namespace App\Services\Transaction\RequestProcessor;

use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Transaction\Crud\Request as TransactionCrudRequest;
use App\Models\Transaction;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Services\Transaction\Schedule\UpdaterContract as TransactionScheduleUpdaterContract;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;

abstract class Base {

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
	 * @param Transaction $transaction
	 * @param TransactionCrudRequest $request
	 * @return $this
	 */
	protected function updateTransactionFromRequest(Transaction $transaction, TransactionCrudRequest $request) {
		$this
			->updateMeta($transaction, $request)
			->updateValue($transaction, $request)
			->updatePeriodicity($transaction, $request);

		return $this;
	}

	/**
	 * @param Transaction $transaction
	 * @param TransactionCrudRequest $request
	 * @return $this
	 */
	private function updateMeta(Transaction $transaction, TransactionCrudRequest $request) {
		$transaction->type = $request->get('type');
		$transaction->name = $request->get('name');
		$transaction->category_id = $request->get('category_id');
		$transaction->description = $request->get('description');
		$transaction->value_type = $request->get('value_type');
		$transaction->periodicity_type = $request->get('periodicity_type');

		return $this;
	}

	/**
	 * @param Transaction $transaction
	 * @param TransactionCrudRequest $request
	 * @return $this
	 * @throws InvalidRequestException
	 */
	private function updateValue(Transaction $transaction, TransactionCrudRequest $request) {
		switch ($request->get('value_type')) {
			case Transaction::VALUE_TYPE_CONSTANT:
				$transactionValue = new TransactionValueConstant();
				$transactionValue->value = $request->get('value_constant_value');
				$transactionValue->saveOrFail();

				$transactionValue
					->transaction()
					->save($transaction);

				break;

			case Transaction::VALUE_TYPE_RANGE:
				$transactionValue = new TransactionValueRange();
				$transactionValue->value_from = $request->get('value_range_from');
				$transactionValue->value_to = $request->get('value_range_to');
				$transactionValue->saveOrfail();

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
	private function updatePeriodicity(Transaction $transaction, TransactionCrudRequest $request) {
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