<?php

namespace App\Services\Transaction\RequestProcessor;

use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Transaction\Crud\Request as TransactionCrudRequest;
use App\Models\Transaction;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
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
	 * @param DatabaseConnection $databaseConnection
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 */
	public function __construct(
		DatabaseConnection $db,
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	) {
		$this->db = $db;
		$this->transactionRepository = $transactionRepository;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
	}

	/**
	 * @param TransactionCrudRequest $request
	 * @param Transaction $transaction
	 * @return $this
	 */
	protected function parseCrudRequest(TransactionCrudRequest $request, Transaction $transaction) {
		$this
			->parseMeta($request, $transaction)
			->parseValue($request, $transaction)
			->parsePeriodicity($request, $transaction);

		return $this;
	}

	/**
	 * @param TransactionCrudRequest $request
	 * @param Transaction $transaction
	 * @return $this
	 */
	private function parseMeta(TransactionCrudRequest $request, Transaction $transaction) {
		$transaction->type = $request->get('type');
		$transaction->name = $request->get('name');
		$transaction->category_id = $request->get('category_id');
		$transaction->description = $request->get('description');
		$transaction->value_type = $request->get('value_type');
		$transaction->periodicity_type = $request->get('periodicity_type');

		return $this;
	}

	/**
	 * @param TransactionCrudRequest $request
	 * @param Transaction $transaction
	 * @return $this
	 */
	private function parseValue(TransactionCrudRequest $request, Transaction $transaction) {
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
	 * @param TransactionCrudRequest $request
	 * @param Transaction $transaction
	 * @return $this
	 * @throws InvalidRequestException
	 */
	private function parsePeriodicity(TransactionCrudRequest $request, Transaction $transaction) {
		if ($transaction->exists) {
			$this->transactionPeriodicityRepository->deleteByTransactionId($transaction->id);
		}

		switch ($request->get('periodicity_type')) {
			case Transaction::PERIODICITY_TYPE_ONE_SHOT:
				foreach ($request->get('calendar_dates') as $calendarDate) {
					$transaction
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
				foreach ($request->get('calendar_dates') as $calendarDate) {
					$date = new Carbon($calendarDate);

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