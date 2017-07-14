<?php

namespace App\Services\Budget;

use App\Http\Requests\Budget\StoreRequest as BudgetStoreRequest;
use App\Models\Budget;
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