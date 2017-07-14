<?php

namespace App\Repositories\Eloquent;

use App\Models\BudgetConsolidation;
use App\Repositories\Contracts\BudgetConsolidationRepositoryContract;

class BudgetConsolidationRepository
	extends AbstractCrudRepository
	implements BudgetConsolidationRepositoryContract {

	/**
	 * @inheritdoc
	 */
	protected function getModelName(): string {
		return BudgetConsolidation::class;
	}

}