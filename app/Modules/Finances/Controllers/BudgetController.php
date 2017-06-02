<?php

namespace App\Modules\Finances\Controllers;

use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Modules\Finances\Services\BudgetTransaction\Search\FindRecentlyBookedTransactionsServiceContract;
use App\Modules\Finances\Http\Requests\Budget\StoreRequest;
use App\Modules\Finances\Models\Budget;
use App\Modules\Finances\Models\BudgetConsolidation;
use App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
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
	 * @var FindRecentlyBookedTransactionsServiceContract
	 */
	protected $findRecentlyBookedTransactionsService;

	/**
	 * @var TransactionScheduleRepositoryContract
	 */
	protected $transactionScheduleRepository;

	/**
	 * BudgetController constructor.
	 * @param BreadcrumbManager $breadcrumbManager
	 * @param BudgetRepositoryContract $budgetRepository
	 * @param TransactionRepositoryContract $budgetTransactionRepository
	 * @param FindRecentlyBookedTransactionsServiceContract $findRecentlyBookedTransactionsService
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		BudgetRepositoryContract $budgetRepository,
		TransactionRepositoryContract $budgetTransactionRepository,
		FindRecentlyBookedTransactionsServiceContract $findRecentlyBookedTransactionsService,
		TransactionScheduleRepositoryContract $transactionScheduleRepository
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->budgetRepository = $budgetRepository;
		$this->budgetTransactionRepository = $budgetTransactionRepository;
		$this->findRecentlyBookedTransactionsService = $findRecentlyBookedTransactionsService;
		$this->transactionScheduleRepository = $transactionScheduleRepository;
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function actionCreate() {
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
			// @todo extract this to a Request class

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

		// prepare recently booked transactions
		$this->findRecentlyBookedTransactionsService
			->reset()
			->setBudgetId($budget->id)
			->setLimit(FindRecentlyBookedTransactionsServiceContract::LIMIT_THIS_MONTH);

		$recentlyBookedTransactions = $this->findRecentlyBookedTransactionsService->getRows();
		$recentlyBookedTransactions =
			$recentlyBookedTransactions
				->sortBy('periodicity.date', SORT_REGULAR, true)
				->take(5); // @todo make this value configurable somewhere for the user

		// prepare incoming transactions
		$dateFrom = new Carbon('now');
		$dateFrom->setTime(0, 0, 0);

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
		]);
	}

}