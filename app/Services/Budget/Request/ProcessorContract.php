<?php

namespace App\Services\Budget\Request;

use App\Http\Requests\Budget\Crud\StoreRequest as BudgetStoreRequest;
use App\Http\Requests\Budget\Crud\UpdateRequest as BudgetUpdateRequest;
use App\ValueObjects\Requests\Budget\StoreResult as BudgetStoreResult;
use App\ValueObjects\Requests\Budget\UpdateResult as BudgetUpdateResult;

interface ProcessorContract {

	/**
	 * @param BudgetStoreRequest $request
	 * @return BudgetStoreResult
	 */
	public function store(BudgetStoreRequest $request): BudgetStoreResult;

	/**
	 * @param BudgetUpdateRequest $request
	 * @param int $id
	 * @return BudgetUpdateResult
	 */
	public function update(BudgetUpdateRequest $request, int $id): BudgetUpdateResult;

	// @todo delete()

}