<?php

namespace App\Services\Budget;

use App\Http\Requests\Budget\Crud\Request as BudgetCrudRequest;
use App\Http\Requests\Budget\Crud\StoreRequest as BudgetStoreRequest;
use App\Http\Requests\Budget\Crud\UpdateRequest as BudgetUpdateRequest;
use App\Models\Budget;
use App\Repositories\Contracts\BudgetConsolidationRepositoryContract;
use App\Repositories\Contracts\BudgetRepositoryContract;
use App\Services\Logger\Contract as LoggerContract;
use App\ValueObjects\Requests\Budget\StoreResult as BudgetStoreResult;
use App\ValueObjects\Requests\Budget\UpdateResult as BudgetUpdateResult;
use Illuminate\Database\Connection as DatabaseConnection;

class RequestProcessor
	implements RequestProcessorContract {

	/**
	 * @var LoggerContract
	 */
	protected $log;

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
	 * @param LoggerContract $log
	 * @param DatabaseConnection $db
	 * @param BudgetRepositoryContract $budgetRepository
	 * @param BudgetConsolidationRepositoryContract $budgetConsolidationRepository
	 */
	public function __construct(
		LoggerContract $log,
		DatabaseConnection $db,
		BudgetRepositoryContract $budgetRepository,
		BudgetConsolidationRepositoryContract $budgetConsolidationRepository
	) {
		$this->log = $log;
		$this->db = $db;
		$this->budgetRepository = $budgetRepository;
		$this->budgetConsolidationRepository = $budgetConsolidationRepository;
	}

	/**
	 * @inheritDoc
	 */
	public function store(BudgetStoreRequest $request): BudgetStoreResult {
		return $this->db->transaction(function () use ($request) {
			$budget = new Budget([
				'type' => $request->get('type'),
				'status' => Budget::STATUS_ACTIVE,
			]);

			$this->fillBudget($budget, $request);

			$this->log->info('Creating new budget [name=%s].', $budget->name);
			$this->budgetRepository->persist($budget);
			$this->log->info('... assigned budget id: [%d].', $budget->id);

			return new BudgetStoreResult($budget);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function update(BudgetUpdateRequest $request, int $id): BudgetUpdateResult {
		return $this->db->transaction(function () use ($request, $id) {
			$this->log->info('Updating budget [id=%d].', $id);

			$budget = $this->budgetRepository->getOrFail($id);
			$this->fillBudget($budget, $request);

			$this->budgetRepository->persist($budget);

			return new BudgetUpdateResult($budget);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function delete(int $id): void {
		$this->db->transaction(function () use ($id) {
			$this->log->info('Deleting budget [id=%d].', $id);
			$this->budgetRepository->delete($id);
		});
	}

	/**
	 * @param Budget $budget
	 * @param BudgetCrudRequest $request
	 * @return $this
	 */
	protected function fillBudget(Budget $budget, BudgetCrudRequest $request) {
		$budget->fill([
			'name' => $request->get('name'),
			'description' => $request->get('description'),
		]);

		$budget
			->consolidatedBudgets()
			->sync($request->get('consolidated_budgets'));

		return $this;
	}

}