<?php

namespace App\Modules\Finances\Repositories\Contracts;

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
	public function getOneShotByIds($periodicityIds, bool $joinTransactions = false);

}