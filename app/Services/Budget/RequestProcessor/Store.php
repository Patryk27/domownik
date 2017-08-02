<?php

namespace App\Services\Budget\RequestProcessor;

use App\Http\Requests\Budget\Crud\StoreRequest as BudgetStoreRequest;
use App\Models\Budget;
use App\Models\BudgetConsolidation;
use App\ValueObjects\Requests\Budget\StoreResult as BudgetStoreResult;

class Store
	extends Base {

	/**
	 * @param BudgetStoreRequest $request
	 * @return BudgetStoreResult
	 */
	public function process(BudgetStoreRequest $request): BudgetStoreResult {
		return $this->db->transaction(function() use ($request) {
			$budget = new Budget();
			$budget->type = $request->get('type');
			$budget->status = Budget::STATUS_ACTIVE;

			$this->updateBudgetFromRequest($budget, $request);
			$this->budgetRepository->persist($budget);

			if ($budget->type === Budget::TYPE_CONSOLIDATED) {
				foreach ($request->get('consolidated_budgets') as $consolidatedBudgetId) {
					$budgetConsolidation = new BudgetConsolidation();
					$budgetConsolidation->base_budget_id = $budget->id;
					$budgetConsolidation->subject_budget_id = $consolidatedBudgetId;

					$this->budgetConsolidationRepository->persist($budgetConsolidation);
				}
			}

			return new BudgetStoreResult($budget);
		});
	}

}