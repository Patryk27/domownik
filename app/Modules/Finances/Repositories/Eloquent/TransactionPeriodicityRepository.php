<?php

namespace App\Modules\Finances\Repositories\Eloquent;

use App\Modules\Finances\Models\TransactionPeriodicityOneShot;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;

class TransactionPeriodicityRepository
	implements TransactionPeriodicityRepositoryContract {

	/**
	 * @inheritDoc
	 */
	public function getOneShotById(int $periodicityId, bool $joinTransaction = false) {
		// @todo cache

		if ($joinTransaction) {
			return
				TransactionPeriodicityOneShot
					::with('transaction', 'transaction.value')
					->find($periodicityId);
		} else {
			return TransactionPeriodicityOneShot::find($periodicityId);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getOneShotByIds(array $periodicityIds, bool $joinTransactions = false) {
		// @todo cache

		if ($joinTransactions) {
			return
				TransactionPeriodicityOneShot
					::with('transaction', 'transaction.value')
					->findMany($periodicityIds);
		} else {
			return TransactionPeriodicityOneShot::findMany($periodicityIds);
		}
	}

}