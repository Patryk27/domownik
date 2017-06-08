<?php

namespace App\Modules\Finances\Repositories\Eloquent;

use App\Modules\Finances\Models\TransactionPeriodicityOneShot;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Support\UsesCache;

class TransactionPeriodicityRepository
	implements TransactionPeriodicityRepositoryContract {

	use UsesCache;

	/**
	 * @inheritDoc
	 */
	public function getOneShotById(int $periodicityId, bool $joinTransaction = false) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, [
			$periodicityId,
			$joinTransaction,
		]);

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

		return $result;
	}

	/**
	 * @inheritDoc
	 */
	public function getOneShotByIds(array $periodicityIds, bool $joinTransactions = false) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, [
			$periodicityIds,
			$joinTransactions,
		]);

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

}