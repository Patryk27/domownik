<?php

namespace App\Modules\Finances\Services\Transaction;

use App\Exceptions\UnexpectedStateException;
use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Models\TransactionPeriodicityOneShot;
use App\Modules\Finances\Models\TransactionValueConstant;
use App\Modules\Finances\Models\TransactionValueRange;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\ServiceContracts\BasicSearchContract;
use App\Support\Facades\Date;
use App\Support\UsesCache;
use Carbon\Carbon;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Support\Collection;
use Illuminate\Cache\Repository as CacheRepository;

class HistoryCollector
	implements HistoryCollectorContract {

	use UsesCache;

	/**
	 * @var CacheRepository
	 */
	protected $cacheRepository;

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
	 * @var Collection|null
	 */
	protected $rows;

	/**
	 * @param CacheRepository $cacheRepository
	 * @param DatabaseConnection $databaseConnection
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 */
	public function __construct(
		CacheRepository $cacheRepository,
		DatabaseConnection $databaseConnection,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	) {
		$this->cacheRepository = $cacheRepository;
		$this->databaseConnection = $databaseConnection;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function reset(): BasicSearchContract {
		$this->rows = null;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getRows(): Collection {
		if (isset($this->rows)) {
			return new Collection($this->rows);
		}

		$cacheKey = $this->getCacheKey(__FUNCTION__, [
			$this->parentType,
			$this->parentId,
			$this->beginDate,
			$this->endDate,
			$this->sortDirection,
		]);

		$this->rows = $this->cacheRepository->rememberForever($cacheKey, function() {
			$stmt = $this->databaseConnection
				->table('transactions AS t')
				->select([
					't.id AS transaction_id',
					'tpos.id AS transaction_periodicity_id',
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
		});

		return $this->rows;
	}

	/**
	 * @inheritDoc
	 */
	public function getRowsForChart(): array {
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
	public function setParentType(string $parentType): HistoryCollectorContract {
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
	public function setParentId(int $parentId): HistoryCollectorContract {
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
	public function setBeginDate($beginDate): HistoryCollectorContract {
		$this->beginDate = Date::stripTime($beginDate);
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
	public function setEndDate($endDate): HistoryCollectorContract {
		$this->endDate = Date::stripTime($endDate);
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
	public function setSortDirection(string $sortDirection): HistoryCollectorContract {
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