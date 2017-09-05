<?php

namespace App\Services\Budget\Request\Processor;

use App\Http\Requests\Budget\Crud\UpdateRequest as BudgetUpdateRequest;
use App\ValueObjects\Requests\Budget\UpdateResult as BudgetUpdateResult;

class Update
	extends Base {

	/**
	 * @param BudgetUpdateRequest $request
	 * @param int $id
	 * @return BudgetUpdateResult
	 */
	public function process(BudgetUpdateRequest $request, int $id): BudgetUpdateResult {
		return $this->db->transaction(function () use ($request, $id) {
			$budget = $this->budgetRepository->getOrFail($id);

			$this->updateBudgetFromRequest($budget, $request);
			$this->budgetRepository->persistUpdate($budget, $id);

			return new BudgetUpdateResult($budget);
		});
	}

}