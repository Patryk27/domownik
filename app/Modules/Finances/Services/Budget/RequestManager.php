<?php

namespace App\Modules\Finances\Services\Budget;

use App\Modules\Finances\Http\Requests\Budget\StoreRequest as BudgetStoreRequest;
use App\Modules\Finances\Models\Budget;
use App\Modules\Finances\Models\BudgetConsolidation;
use App\Modules\Finances\Repositories\Contracts\BudgetConsolidationRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract;
use App\Modules\Finances\Repositories\Eloquent\BudgetConsolidationRepository;
use Illuminate\Database\Connection as DatabaseConnection;

class RequestManager
	implements RequestManagerContract {

	/**
	 * @var DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var BudgetRepositoryContract
	 */
	protected $budgetRepository;

	/**
	 * @var BudgetConsolidationRepository
	 */
	protected $budgetConsolidationRepository;

	/**
	 * @var Budget
	 */
	protected $budget;

	/**
	 * @param DatabaseConnection $databaseConnection
	 * @param BudgetRepositoryContract $budgetRepository
	 * @param BudgetConsolidationRepositoryContract $budgetConsolidationRepository
	 */
	public function __construct(
		DatabaseConnection $databaseConnection,
		BudgetRepositoryContract $budgetRepository,
		BudgetConsolidationRepositoryContract $budgetConsolidationRepository
	) {
		$this->databaseConnection = $databaseConnection;
		$this->budgetRepository = $budgetRepository;
		$this->budgetConsolidationRepository = $budgetConsolidationRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function store(BudgetStoreRequest $request): string {
		$this->budget = null;

		return $this->databaseConnection->transaction(function() use ($request) {
			// create budget
			$budget = new Budget();
			$budget->type = $request->get('budgetType');
			$budget->name = $request->get('budgetName');
			$budget->description = $request->get('budgetDescription');
			$budget->status = Budget::STATUS_ACTIVE;

			$this->budgetRepository->persist($budget);

			// create budget consolidations
			if ($budget->type === Budget::TYPE_CONSOLIDATED) {
				foreach ($request->get('consolidatedBudgets') as $budgetId) {
					$budgetConsolidation = new BudgetConsolidation();
					$budgetConsolidation->base_budget_id = $budget->id;
					$budgetConsolidation->subject_budget_id = $budgetId;

					$this->budgetConsolidationRepository->persist($budgetConsolidation);
				}
			}

			$this->budget = $budget;
			
			return self::STORE_RESULT_CREATED;
		});
	}

	/**
	 * @return Budget
	 */
	public function getBudget(): Budget {
		return $this->budget;
	}

}