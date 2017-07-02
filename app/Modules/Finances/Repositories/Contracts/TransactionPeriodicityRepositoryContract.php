<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\Models\TransactionPeriodicityMonthly;
use App\Modules\Finances\Models\TransactionPeriodicityOneShot;
use Illuminate\Support\Collection;

interface TransactionPeriodicityRepositoryContract {

	/**
	 * @param int $periodicityId
	 * @param bool $joinTransaction
	 * @return TransactionPeriodicityOneShot|null
	 */
	public function getOneShotById(int $periodicityId, bool $joinTransaction = false);

	/**
	 * @param int[] $periodicityIds
	 * @param bool $joinTransactions
	 * @return Collection|TransactionPeriodicityOneShot[]
	 */
	public function getOneShotByIds(array $periodicityIds, bool $joinTransactions = false): Collection;

	/**
	 * @param int $transactionId
	 * @return Collection|TransactionPeriodicityMonthly[]
	 */
	public function getMonthliesByTransactionId(int $transactionId): Collection;

}