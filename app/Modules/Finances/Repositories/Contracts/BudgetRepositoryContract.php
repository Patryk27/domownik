<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\Models\Budget;
use App\Repositories\Contracts\CrudRepositoryContract;
use Illuminate\Support\Collection;

interface BudgetRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * @return Collection|Budget[]
	 */
	public function getActiveBudgets();

}