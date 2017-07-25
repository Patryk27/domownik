<?php

namespace App\Services\Search\Transaction;

use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Services\Search\Filters\DateFilter;
use App\Services\Search\Search;
use App\Services\Search\Transaction\Filters\NameFilter as TransactionNameFilter;
use App\Services\Search\Transaction\Filters\ParentFilter as TransactionParentFilter;
use App\ValueObjects\ScheduledTransaction;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Support\Collection;
use stdClass;

class ScheduleSearch
	extends Search
	implements ScheduleSearchContract {

	/**
	 * @var TransactionRepositoryContract
	 */
	protected $transactionRepository;

	/**
	 * @param DatabaseConnection $db
	 * @param TransactionRepositoryContract $transactionRepository
	 */
	public function __construct(
		DatabaseConnection $db,
		TransactionRepositoryContract $transactionRepository
	) {
		parent::__construct($db);

		$this->transactionRepository = $transactionRepository;
	}

	/**
	 * @inheritdoc
	 */
	public function reset() {
		parent::reset();

		$this->builder
			->from('transaction_schedules AS ts')
			->join('transactions AS t', 't.id', '=', 'ts.transaction_id');

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function parent(string $parentType, int $parentId) {
		return $this->addFilter(new TransactionParentFilter($parentType, $parentId));
	}

	/**
	 * @inheritdoc
	 */
	public function date(string $operator, $date) {
		return $this->addFilter(new DateFilter('ts.date', $operator, $date));
	}

	/**
	 * @inheritDoc
	 */
	public function name(string $name) {
		return $this->addFilter(new TransactionNameFilter(TransactionNameFilter::OP_CONTAINS, $name));
	}

	/**
	 * @inheritdoc
	 */
	public function get(): Collection {
		$this->applyFilters();

		return $this->builder
			->get([
				'ts.id',
				'ts.transaction_id',
				'ts.date',
			])
			->map(function(stdClass $row) {
				$transaction = $this->transactionRepository->getOrFail($row->transaction_id);

				return new ScheduledTransaction(
					$row->id,
					$transaction,
					new Carbon($row->date)
				);
			});
	}

}