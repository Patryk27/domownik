<?php

namespace App\Modules\Finances\Services\BudgetTransaction\Search;

use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\ServiceContracts\BasicSearchContract;
use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Support\Collection;

class FindRecentlyBookedTransactionsService
	implements FindRecentlyBookedTransactionsServiceContract {

	/**
	 * @var Connection
	 */
	protected $databaseConnection;

	/**
	 * @var Carbon
	 */
	protected $carbon;

	/**
	 * @var int
	 */
	protected $budgetId;

	/**
	 * @var TransactionRepositoryContract
	 */
	protected $transactionRepository;

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

	/**
	 * @var string
	 */
	protected $limit;

	/**
	 * FindLastTransactionsService constructor.
	 * @param Connection $databaseConnection
	 * @param Carbon $carbon
	 * @param BudgetRepositoryContract|TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityOneShotRepository
	 */
	public function __construct(
		Connection $databaseConnection,
		Carbon $carbon,
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityOneShotRepository
	) {
		$this->databaseConnection = $databaseConnection;
		$this->carbon = $carbon;
		$this->transactionRepository = $transactionRepository;
		$this->transactionPeriodicityRepository = $transactionPeriodicityOneShotRepository;

		$this->reset();
	}

	/**
	 * @inheritdoc
	 */
	public function reset(): BasicSearchContract {
		$this->budgetId = null;
		$this->limit = null;

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getRows(): Collection {
		$now = new $this->carbon('now');

		$rows =
			$this->databaseConnection
				->table('transactions AS t')
				->select([
					't.id AS transaction_id',
					'tpos.id AS transaction_periodicity_id'
				])
				->leftJoin('transaction_periodicities AS tp', 'tp.transaction_id', '=', 't.id')
				->leftJoin('transaction_periodicity_one_shots AS tpos', 'tpos.id', '=', 'tp.transaction_periodicity_id')
				->where('t.parent_type', Transaction::PARENT_TYPE_BUDGET)
				->where('t.parent_id', $this->budgetId)
				->where('tp.transaction_periodicity_type', Transaction::PERIODICITY_TYPE_ONE_SHOT)
				->where('tpos.date', '<=', $now)
				->orderBy('tpos.date', 'desc')
				->groupBy('t.id', 'tpos.id')
				->get();

		$transactionPeriodicityIds = array_column($rows->toArray(), 'transaction_periodicity_id');
		$transactionPeriodicities = $this->transactionPeriodicityRepository->getOneShotByIds($transactionPeriodicityIds, true);

		$result = new Collection();

		foreach ($transactionPeriodicities as $transactionPeriodicity) {
			$transaction = $transactionPeriodicity->transaction[0];
			$transaction->periodicity = $transactionPeriodicity;

			$result->push($transaction);
		}

		return $result;
	}

	/**
	 * @return int
	 */
	public function getBudgetId(): int {
		return $this->budgetId;
	}

	/**
	 * @inheritdoc
	 */
	public function setBudgetId(int $budgetId): FindRecentlyBookedTransactionsServiceContract {
		$this->budgetId = $budgetId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLimit(): string {
		return $this->limit;
	}

	/**
	 * @inheritdoc
	 */
	public function setLimit(string $limit): FindRecentlyBookedTransactionsServiceContract {
		$this->limit = $limit;
		return $this;
	}

}