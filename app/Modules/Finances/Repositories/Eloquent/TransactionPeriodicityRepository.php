<?php

namespace App\Modules\Finances\Repositories\Eloquent;

use App\Models\Model;
use App\Modules\Finances\Models\TransactionPeriodicityMonthly;
use App\Modules\Finances\Models\TransactionPeriodicityOneShot;
use App\Modules\Finances\Models\TransactionPeriodicityWeekly;
use App\Modules\Finances\Models\TransactionPeriodicityYearly;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Support\UsesCache;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

class TransactionPeriodicityRepository
	implements TransactionPeriodicityRepositoryContract {

	use UsesCache;

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @param DatabaseConnection $db
	 */
	public function __construct(
		DatabaseConnection $db
	) {
		$this->db = $db;
	}

	/**
	 * @inheritDoc
	 */
	public function getOneShotById(int $periodicityId, bool $joinTransaction = false) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = TransactionPeriodicityOneShot::getCache();

		return $cache->rememberForever($cacheKey, function() use ($periodicityId, $joinTransaction) {
			switch ($joinTransaction) {
				case true:
					return
						TransactionPeriodicityOneShot
							::with('transaction', 'transaction.value')
							->find($periodicityId);

				default:
					return TransactionPeriodicityOneShot::find($periodicityId);
			}
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getOneShotByIds(array $periodicityIds, bool $joinTransactions = false): Collection {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = TransactionPeriodicityOneShot::getCache();

		return $cache->rememberForever($cacheKey, function() use ($periodicityIds, $joinTransactions) {
			switch ($joinTransactions) {
				case true:
					return
						TransactionPeriodicityOneShot
							::with('transaction', 'transaction.value')
							->findMany($periodicityIds);

				default:
					return TransactionPeriodicityOneShot::findMany($periodicityIds);
			}
		});
	}

	/**
	 * @inheritdoc
	 */
	public function getOneShotsByTransactionId(int $transactionId): Collection {
		return $this->getPeriodicities('transaction_periodicity_one_shots', TransactionPeriodicityOneShot::class, 'transaction-periodicity-one-shot', $transactionId);
	}

	/**
	 * @inheritdoc
	 */
	public function getWeekliesByTransactionId(int $transactionId): Collection {
		return $this->getPeriodicities('transaction_periodicity_weeklies', TransactionPeriodicityWeekly::class, 'transaction-periodicity-weekly', $transactionId);
	}

	/**
	 * @inheritDoc
	 */
	public function getMonthliesByTransactionId(int $transactionId): Collection {
		return $this->getPeriodicities('transaction_periodicity_monthlies', TransactionPeriodicityMonthly::class, 'transaction-periodicity-monthly', $transactionId);
	}

	/**
	 * @inheritDoc
	 */
	public function getYearliesByTransactionId(int $transactionId): Collection {
		return $this->getPeriodicities('transaction_periodicity_yearlies', TransactionPeriodicityYearly::class, 'transaction-periodicity-yearly', $transactionId);
	}

	/**
	 * @param string $periodicityTableName
	 * @param string $periodicityModelClass
	 * @param string $periodicityType
	 * @param int $transactionId
	 * @return Collection
	 */
	protected function getPeriodicities(string $periodicityTableName, string $periodicityModelClass, string $periodicityType, int $transactionId): Collection {
		// check cache
		/**
		 * @var Model $periodicityModel
		 */
		$periodicityModel = new $periodicityModelClass();

		$cache = $periodicityModel::getCache();
		$cacheKey = $this->getCacheKey( __METHOD__, [$transactionId]);

		return $cache->rememberForever($cacheKey, function() use ($periodicityTableName, $periodicityModelClass, $periodicityType, $transactionId) {
			$stmt = $this->db
				->query()
				->select('tpk.*')
				->from(sprintf('%s AS tpk', $periodicityTableName))
				->leftJoin('transaction_periodicities AS tp', function(JoinClause $join) use ($periodicityType) {
					$join->on('tp.transaction_periodicity_id', '=', 'tpk.id');
					$join->where('tp.transaction_periodicity_type', '=', $periodicityType);
				})
				->where('tp.transaction_id', $transactionId);

			$result = new Collection();

			foreach ($stmt->get() as $row) {
				$result->push(new $periodicityModelClass((array)$row));
			}

			return $result;
		});
	}

}