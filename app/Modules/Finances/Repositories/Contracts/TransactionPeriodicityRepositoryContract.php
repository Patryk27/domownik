<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\Models\TransactionPeriodicityMonthly;
use App\Modules\Finances\Models\TransactionPeriodicityOneShot;
use App\Modules\Finances\Models\TransactionPeriodicityWeekly;
use App\Modules\Finances\Models\TransactionPeriodicityYearly;
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
	 * Returns all one shot periodicities linked to given transaction.
	 * @param int $transactionId
	 * @return Collection|TransactionPeriodicityOneShot[]
	 */
	public function getOneShotsByTransactionId(int $transactionId): Collection;

	/**
	 * Returns all weekly periodicities linked to given transaction.
	 * @param int $transactionId
	 * @return Collection|TransactionPeriodicityWeekly[]
	 */
	public function getWeekliesByTransactionId(int $transactionId): Collection;

	/**
	 * Returns all monthly periodicities linked to given transaction.
	 * @param int $transactionId
	 * @return Collection|TransactionPeriodicityMonthly[]
	 */
	public function getMonthliesByTransactionId(int $transactionId): Collection;

	/**
	 * Returns all yearly periodicities linked to given transaction.
	 * @param int $transactionId
	 * @return Collection|TransactionPeriodicityYearly[]
	 */
	public function getYearliesByTransactionId(int $transactionId): Collection;

	/**
	 * Deletes all the transaction's periodicities.
	 * @param int $transactionId
	 * @return TransactionPeriodicityRepositoryContract
	 */
	public function deleteByTransactionId(int $transactionId): TransactionPeriodicityRepositoryContract;

}