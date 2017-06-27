<?php

namespace App\Modules\Finances\Services\Budget;

use App\Modules\Finances\Http\Requests\Budget\StoreRequest as BudgetStoreRequest;
use App\Modules\Finances\Models\Budget;
use App\ServiceContracts\RequestManagerContract as BaseRequestManagerContract;

interface RequestManagerContract
	extends BaseRequestManagerContract {

	/**
	 * @param BudgetStoreRequest $request
	 * @return string
	 */
	public function store(BudgetStoreRequest $request): string;

	/**
	 * @return Budget
	 */
	public function getBudget(): Budget;

}