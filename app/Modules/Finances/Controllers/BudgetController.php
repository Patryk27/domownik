<?php

namespace App\Modules\Finances\Controllers;

use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Modules\Finances\Http\Requests\Budget\StoreRequest;
use App\Modules\Finances\Models\Budget;
use App\Modules\Finances\Models\BudgetConsolidation;
use App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Services\Transaction\HistoryCollectorServiceContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\Support\Facades\Date;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BudgetController
	extends \App\Http\Controllers\Controller {

	/**
	 * @var BreadcrumbManager
	 */
	protected $breadcrumbManager;

	/**
	 * @var BudgetRepositoryContract
	 */
	protected $budgetRepository;

	/**
	 * @var TransactionRepositoryContract
	 */
	protected $budgetTransactionRepository;

	/**
	 * @var TransactionScheduleRepositoryContract
	 */
	protected $transactionScheduleRepository;

	/**
	 * @var HistoryCollectorServiceContract
	 */
	protected $transactionHistoryCollectorService;

	/**
	 * BudgetController constructor.
	 * @param BreadcrumbManager $breadcrumbManager
	 * @param BudgetRepositoryContract $budgetRepository
	 * @param TransactionRepositoryContract $budgetTransactionRepository
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param HistoryCollectorServiceContract $historyCollectorService
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		BudgetRepositoryContract $budgetRepository,
		TransactionRepositoryContract $budgetTransactionRepository,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		HistoryCollectorServiceContract $transactionHistoryCollectorService
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->budgetRepository = $budgetRepository;
		$this->budgetTransactionRepository = $budgetTransactionRepository;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
		$this->transactionHistoryCollectorService = $transactionHistoryCollectorService;
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionCreate() {
		$this->breadcrumbManager->push(route('finances.budget.create'), __('Finances::breadcrumb.budget.create'));

		return view('Finances::budget/create', [
			'budgetTypes' => Budget::getTypes(),
			'activeBudgets' => $this->budgetRepository->getActiveBudgets(),
		]);
	}

	/**
	 * @param StoreRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionStore(StoreRequest $request) {
		return DB::transaction(function() use ($request) {
			// @todo extract this to a RequestManager class

			// create budget
			$budget = new Budget();
			$budget->type = $request->get('budgetType');
			$budget->name = $request->get('budgetName');
			$budget->description = $request->get('budgetDescription');
			$budget->status = Budget::STATUS_ACTIVE;
			$budget->saveOrFail();

			// create budget consolidations
			if ($budget->type === Budget::TYPE_CONSOLIDATED) {
				foreach ($request->get('consolidatedBudgets') as $budgetId) {
					$budgetConsolidation = new BudgetConsolidation();
					$budgetConsolidation->base_budget_id = $budget->id;
					$budgetConsolidation->subject_budget_id = $budgetId;
					$budgetConsolidation->saveOrFail();
				}
			}

			flash(__('Finances::requests/budget/store.messages.success', [
				'budgetName' => $budget->name,
			]), 'success');

			return response()->json([
				'redirectUrl' => route('finances.budget.show', $budget->id)
			]);
		});
	}

	/**
	 * @param Budget $budget
	 * @return \Illuminate\Http\Response
	 */
	public function actionShow(Budget $budget) {
		$this->breadcrumbManager->pushCustom($budget);

		// prepare budget history
		$this->transactionHistoryCollectorService
			->reset()
			->setParentType(Transaction::PARENT_TYPE_BUDGET)
			->setParentId($budget->id)
			->setEndDate(new Carbon('now'))
			->setSortDirection('asc');

		$budgetHistoryRows = $this->transactionHistoryCollectorService->getRowsForChart();

		$recentlyBookedTransactions =
			$this->transactionHistoryCollectorService
				->getRows()
				->reverse()
				->take(5); // @todo make this value configurable somewhere for the user

		// prepare incoming transactions
		$dateFrom = Date::stripTime(new Carbon('now'));

		$dateTo =
			$dateFrom
				->copy()
				->addYear();

		$incomingTransactions = $this->transactionScheduleRepository->getByBudgetId($budget->id, $dateFrom, $dateTo);
		$incomingTransactions = $incomingTransactions->take(5); // @todo make this value configurable somewhere for the user

		return view('Finances::budget/show', [
			'budget' => $budget,
			'recentlyBookedTransactions' => $recentlyBookedTransactions,
			'incomingTransactions' => $incomingTransactions,
			'budgetHistoryRows' => json_encode($budgetHistoryRows),
		]);
	}

}