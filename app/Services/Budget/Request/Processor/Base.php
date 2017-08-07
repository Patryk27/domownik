<?php

namespace App\Services\Budget\Request\Processor;

use App\Http\Requests\Budget\Crud\Request;
use App\Models\Budget;
use App\Repositories\Contracts\BudgetConsolidationRepositoryContract;
use App\Repositories\Contracts\BudgetRepositoryContract;
use Illuminate\Database\Connection as DatabaseConnection;

abstract class Base {

	/**
	 * @var DatabaseConnection
	 */
	protected $db;

	/**
	 * @var BudgetRepositoryContract
	 */
	protected $budgetRepository;

	/**
	 * @var BudgetConsolidationRepositoryContract
	 */
	protected $budgetConsolidationRepository;

	/**
	 * @param DatabaseConnection $db
	 * @param BudgetRepositoryContract $budgetRepository
	 * @param BudgetConsolidationRepositoryContract $budgetConsolidationRepository
	 */
	public function __construct(
		DatabaseConnection $db,
		BudgetRepositoryContract $budgetRepository,
		BudgetConsolidationRepositoryContract $budgetConsolidationRepository
	) {
		$this->db = $db;
		$this->budgetRepository = $budgetRepository;
		$this->budgetConsolidationRepository = $budgetConsolidationRepository;
	}

	/**
	 * @param Budget $budget
	 * @param Request $request
	 * @return $this
	 */
	protected function updateBudgetFromRequest(Budget $budget, Request $request) {
		$budget->name = $request->get('name');
		$budget->description = $request->get('description');

		$budget
			->consolidatedBudgets()
			->sync($request->get('consolidated_budgets'));

		return $this;
	}

}