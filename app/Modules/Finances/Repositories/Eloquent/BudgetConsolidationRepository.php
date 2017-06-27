<?php

namespace App\Modules\Finances\Repositories\Eloquent;

use App\Modules\Finances\Models\BudgetConsolidation;
use App\Modules\Finances\Repositories\Contracts\BudgetConsolidationRepositoryContract;
use App\Repositories\Eloquent\AbstractCrudRepository;

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