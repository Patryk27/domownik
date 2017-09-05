<?php

namespace App\Services\Budget\Request\Processor;

use App\Http\Requests\Budget\Crud\StoreRequest as BudgetStoreRequest;
use App\Models\Budget;
use App\ValueObjects\Requests\Budget\StoreResult as BudgetStoreResult;

class Store
	extends Base {

	/**
	 * @param BudgetStoreRequest $request
	 * @return BudgetStoreResult
	 */
	public function process(BudgetStoreRequest $request): BudgetStoreResult {
		return $this->db->transaction(function () use ($request) {
			$budget = new Budget();
			$budget->type = $request->get('type');
			$budget->status = Budget::STATUS_ACTIVE;

			$this->updateBudgetFromRequest($budget, $request);
			$this->budgetRepository->persist($budget);

			return new BudgetStoreResult($budget);
		});
	}

}