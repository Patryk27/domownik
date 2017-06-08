<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Exceptions\UnexpectedStateException;
use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionPeriodicityOneShot;
use App\Modules\Finances\Models\TransactionValueConstant;
use App\Modules\Finances\Models\TransactionValueRange;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\ServiceContracts\BasicSearchContract;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Support\Collection;

class HistoryCollectorService
	implements HistoryCollectorServiceContract {

	/**
	 * @var DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

	/**
	 * @var string
	 */
	protected $parentType;

	/**
	 * @var int
	 */
	protected $parentId;

	/**
	 * @var Carbon|null
	 */
	protected $beginDate;

	/**
	 * @var Carbon|null
	 */
	protected $endDate;

	/**
	 * @var string
	 */
	protected $sortDirection;

	/**
	 * HistoryCollectorService constructor.
	 * @param DatabaseConnection $databaseConnection
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 */
	public function __construct(
		DatabaseConnection $databaseConnection,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	) {
		$this->databaseConnection = $databaseConnection;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function reset(): BasicSearchContract {
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getRows(): Collection {
		// prepare rows
		$stmt = $this->databaseConnection
			->table('transactions AS t')
			->select([
				't.id AS transaction_id',
				'tpos.id AS transaction_periodicity_id'
			])
			->leftJoin('transaction_periodicities AS tp', 'tp.transaction_id', '=', 't.id')
			->leftJoin('transaction_periodicity_one_shots AS tpos', 'tpos.id', '=', 'tp.transaction_periodicity_id')
			->where('t.parent_type', $this->parentType)
			->where('t.parent_id', $this->parentId)
			->where('tp.transaction_periodicity_type', Transaction::PERIODICITY_TYPE_ONE_SHOT);

		if (isset($this->beginDate)) {
			$stmt->where('tpos.date', '>=', $this->beginDate);
		}

		if (isset($this->endDate)) {
			$stmt->where('tpos.date', '<=', $this->endDate);
		}

		$rows = $stmt->get();

		// parse rows
		$transactionPeriodicityIds = array_column($rows->toArray(), 'transaction_periodicity_id');
		$transactionPeriodicities = $this->transactionPeriodicityRepository->getOneShotByIds($transactionPeriodicityIds, true);

		$result = new Collection();

		foreach ($transactionPeriodicities as $transactionPeriodicity) {
			$transaction = $transactionPeriodicity->transaction[0];

			$transaction->periodicity = $transactionPeriodicity;
			$transaction->periodicity_type = Transaction::PERIODICITY_TYPE_ONE_SHOT;

			$result->push($transaction);
		}

		$result = $result->sortBy('periodicity.date.timestamp', SORT_REGULAR, $this->sortDirection === self::SORT_DIRECTION_DESCENDING);

		return $result;
	}

	/**
	 * @inheritDoc
	 */
	public function getRowsForChart(): array {
		// @todo can be optimized (no need for building that whole transaction-collection bubble)

		$rows = $this->getRows();

		$rows = $rows
			->map(function(Transaction $transaction) {
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

		return $rows;
	}

	/**
	 * @inheritDoc
	 */
	public function getParentType(): string {
		return $this->parentType;
	}

	/**
	 * @inheritDoc
	 */
	public function setParentType(string $parentType): HistoryCollectorServiceContract {
		$this->parentType = $parentType;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getParentId(): int {
		return $this->parentId;
	}

	/**
	 * @inheritDoc
	 */
	public function setParentId(int $parentId): HistoryCollectorServiceContract {
		$this->parentId = $parentId;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getBeginDate() {
		return $this->beginDate;
	}

	/**
	 * @inheritDoc
	 */
	public function setBeginDate($beginDate): HistoryCollectorServiceContract {
		$this->beginDate = $beginDate;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * @inheritDoc
	 */
	public function setEndDate($endDate): HistoryCollectorServiceContract {
		$this->endDate = $endDate;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getSortDirection(): string {
		return $this->sortDirection;
	}

	/**
	 * @inheritDoc
	 */
	public function setSortDirection(string $sortDirection): HistoryCollectorServiceContract {
		$this->sortDirection = $sortDirection;
		return $this;
	}

	/**
	 * @param Transaction $transaction
	 * @return float|null
	 */
	protected function getTransactionValue(Transaction $transaction) {
		switch ($transaction->value_type) {
			case Transaction::VALUE_TYPE_CONSTANT:
				/**
				 * @var TransactionValueConstant $transactionValue
				 */
				$transactionValue = $transaction->value;

				return $transactionValue->value;

			case Transaction::VALUE_TYPE_RANGE:
				/**
				 * @var TransactionValueRange $transactionValue
				 */
				$transactionValue = $transaction->value;

				return ($transactionValue->value_from + $transactionValue->value_to) / 2.0;

			default:
				throw new UnexpectedStateException('Transaction value type not known: %s.', $transaction->value_type);
		}
	}

}