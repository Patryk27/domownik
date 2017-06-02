<?php

namespace App\Modules\Finances\Repositories\Eloquent;

use App\Modules\Finances\Models\Budget;
use App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract;
use App\Repositories\Eloquent\AbstractCrudRepository;

class BudgetRepository
	extends AbstractCrudRepository
	implements BudgetRepositoryContract {

	/**
	 * @inheritdoc
	 */
	public function getActiveBudgets() {
		return Budget::where('status', Budget::STATUS_ACTIVE)
					 ->get();
	}

	/**
	 * @inheritdoc
	 */
	protected function getModelName() {
		return Budget::class;
	}

}