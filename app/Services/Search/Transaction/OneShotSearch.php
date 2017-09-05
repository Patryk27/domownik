<?php

namespace App\Services\Search\Transaction;

use App\Exceptions\UnexpectedStateException;
use App\Models\Transaction;
use App\Models\TransactionPeriodicityOneShot;
use App\Models\TransactionValueConstant;
use App\Models\TransactionValueRange;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Services\Search\Filters\StringFilter;
use App\Services\Search\Search;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Support\Collection;

class OneShotSearch
	extends Search
	implements OneShotSearchContract {

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

	/**
	 * @param DatabaseConnection $db
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 */
	public function __construct(
		DatabaseConnection $db,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	) {
		parent::__construct($db);

		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
	}

	/**
	 * @inheritdoc
	 */
	public function reset() {
		parent::reset();

		$this->builder
			->from('transactions AS t')
			->select([
				't.id AS transaction_id',
				'tpos.id AS transaction_periodicity_id',
			])
			->leftJoin('transaction_periodicities AS tp', 'tp.transaction_id', '=', 't.id')
			->leftJoin('transaction_periodicity_one_shots AS tpos', 'tpos.id', '=', 'tp.transaction_periodicity_id')
			->where('tp.transaction_periodicity_type', 'transaction-periodicity-one-shot');

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function parent(string $parentType, int $parentId) {
		$this->builder
			->where('t.parent_type', $parentType)
			->where('t.parent_id', $parentId);

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function date(string $operator, $date) {
		$this->builder->where('tpos.date', $operator, $date);
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function name(string $name) {
		return $this->applyFilter(new StringFilter('t.name', StringFilter::OP_CONTAINS, $name));
	}

	/**
	 * @inheritdoc
	 */
	public function get(): Collection {
		$rows = parent::get();

		$transactionPeriodicityIds =
			$rows
				->pluck('transaction_periodicity_id')
				->all();

		$transactionPeriodicities =
			$this->transactionPeriodicityRepository
				->getOneShotByIds($transactionPeriodicityIds, true)
				->keyBy('id');

		$result = new Collection();

		/**
		 * We're iterating basing on $transactionPeriodicityIds and not $transactionPeriodicities because the latter
		 * may have other order than the original one we need.
		 */
		foreach ($transactionPeriodicityIds as $transactionPeriodicityId) {
			$transactionPeriodicity = $transactionPeriodicities->get($transactionPeriodicityId);

			$transaction = $transactionPeriodicity->transaction[0];

			$transaction->periodicity = $transactionPeriodicity;
			$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_ONE_SHOT;

			$result->push($transaction);
		}

		return $result;
	}

	/**
	 * @inheritDoc
	 */
	public function getChart(): array {
		return $this
			->get()
			->sortBy('periodicity.date.timestamp', SORT_REGULAR)
			->map(function (Transaction $transaction) {
				/**
				 * @var TransactionPeriodicityOneShot $transactionPeriodicity
				 */
				$transactionPeriodicity = $transaction->periodicity;

				$date = $transactionPeriodicity->date;
				$value = $this->getTransactionValue($transaction);

				return [
					[$date->year, $date->month, $date->day],
					round($value, 2),
				];
			})
			->values()
			->toArray();
	}

	/**
	 * @param Transaction $transaction
	 * @return float
	 * @throws UnexpectedStateException
	 */
	protected function getTransactionValue(Transaction $transaction): float {
		switch ($transaction->value_type) {
			case Transaction::VALUE_TYPE_CONSTANT:
				/**
				 * @var TransactionValueConstant $value
				 */
				$value = $transaction->value;

				return $value->value;

			case Transaction::VALUE_TYPE_RANGE:
				/**
				 * @var TransactionValueRange $value
				 */
				$value = $transaction->value;

				return ($value->value_from + $value->value_to) / 2.0;

			default:
				throw new UnexpectedStateException('Unexpected transaction value type [%s].', $transaction->value_type);
		}
	}

}