<?php

namespace App\Services\Search\Transaction;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Services\Search\Filters\Transaction\OneShot\Date as OneShotDateFilter;
use App\Services\Search\Filters\Transaction\ParentTypeAndId as ParentTypeAndIdFilter;
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
			->leftJoin('transaction_periodicity_one_shots AS tpos', 'tpos.id', '=', 'tp.transaction_periodicity_id');

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function parentTypeAndId(string $parentType, int $parentId) {
		return $this->addFilter(new ParentTypeAndIdFilter($parentType, $parentId));
	}

	/**
	 * @inheritdoc
	 */
	public function date(string $operator, $date) {
		return $this->addFilter(new OneShotDateFilter($operator, $date));
	}

	/**
	 * @inheritdoc
	 */
	public function get(): Collection {
		$rows = parent::get();

		$transactionPeriodicityIds = array_column($rows->all(), 'transaction_periodicity_id');
		$transactionPeriodicities = $this->transactionPeriodicityRepository->getOneShotByIds($transactionPeriodicityIds, true);

		$result = new Collection();

		foreach ($transactionPeriodicities as $transactionPeriodicity) {
			$transaction = $transactionPeriodicity->transaction[0];

			$transaction->periodicity = $transactionPeriodicity;
			$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_ONE_SHOT;

			$result->push($transaction);
		}

		//$result = $result->sortBy('periodicity.date.timestamp', SORT_REGULAR, $this->sortDirection === self::SORT_DIRECTION_DESCENDING);

		return $result;
	}

}