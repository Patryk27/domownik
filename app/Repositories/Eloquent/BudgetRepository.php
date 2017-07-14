<?php

namespace App\Repositories\Eloquent;

use App\Models\Budget;
use App\Repositories\Contracts\BudgetRepositoryContract;

class BudgetRepository
	extends AbstractCrudRepository
	implements BudgetRepositoryContract {

	/**
	 * @inheritdoc
	 */
	public function getActiveBudgets() {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() {
			return
				Budget::where('status', Budget::STATUS_ACTIVE)
					  ->get();
		});
	}

	/**
	 * @inheritdoc
	 */
	protected function getModelName(): string {
		return Budget::class;
	}

}