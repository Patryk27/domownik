<?php

namespace App\Modules\Finances\Services\BudgetTransaction\Search;

use App\Modules\Finances\Models\Transaction;
use App\ServiceContracts\BasicSearchContract;
use Illuminate\Support\Collection;

/**
 * Provides an interface for getting recently
 */
interface FindRecentlyBookedTransactionsServiceContract
	extends BasicSearchContract {

	const
		LIMIT_THIS_WEEK = 'limit-this-week',
		LIMIT_THIS_MONTH = 'limit-this-month';

	/**
	 * @param int $budgetId
	 * @return $this
	 */
	public function setBudgetId(int $budgetId): FindRecentlyBookedTransactionsServiceContract;

	/**
	 * @param string $limit
	 * @return $this
	 */
	public function setLimit(string $limit): FindRecentlyBookedTransactionsServiceContract;

	/**
	 * @return Collection|Transaction[]
	 */
	public function getRows(): Collection;

}