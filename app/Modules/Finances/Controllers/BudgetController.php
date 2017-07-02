<?php

namespace App\Modules\Finances\Controllers;

use App\Modules\Finances\Http\Requests\Budget\StoreRequest as BudgetStoreRequest;
use App\Modules\Finances\Models\Budget;
use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Modules\Finances\Services\Budget\RequestManagerContract as BudgetRequestManagerContract;
use App\Modules\Finances\Services\Transaction\HistoryCollectorContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\Support\Facades\Date;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BudgetController
	extends \App\Http\Controllers\Controller {

	/**
	 * @var BreadcrumbManager
	 */
	protected $breadcrumbManager;

	/**
	 * @var BudgetRequestManagerContract
	 */
	protected $budgetRequestManager;

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
	 * @var HistoryCollectorContract
	 */
	protected $transactionHistoryCollectorService;

	/**
	 * @param BreadcrumbManager $breadcrumbManager
	 * @param BudgetRequestManagerContract $budgetRequestManager
	 * @param BudgetRepositoryContract $budgetRepository
	 * @param TransactionRepositoryContract $budgetTransactionRepository
	 * @param TransactionScheduleRepositoryContract $transactionScheduleRepository
	 * @param HistoryCollectorContract $transactionHistoryCollectorService
	 * @internal param HistoryCollectorContract $historyCollectorService
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		BudgetRequestManagerContract $budgetRequestManager,
		BudgetRepositoryContract $budgetRepository,
		TransactionRepositoryContract $budgetTransactionRepository,
		TransactionScheduleRepositoryContract $transactionScheduleRepository,
		HistoryCollectorContract $transactionHistoryCollectorService
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->budgetRequestManager = $budgetRequestManager;
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
	 * @param BudgetStoreRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionStore(BudgetStoreRequest $request) {
		$this->budgetRequestManager->store($request);

		$budget = $this->budgetRequestManager->getBudget();

		flash(__('Finances::requests/budget/store.messages.success', [
			'budgetName' => $budget->name,
		]), 'success');

		return response()->json([
			'redirectUrl' => route('finances.budget.show', $budget->id),
		]);
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

		$recentTransactionsChart = $this->transactionHistoryCollectorService->getRowsForChart();

		$recentTransactions =
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
			'recentTransactions' => $recentTransactions,
			'recentTransactionsChart' => json_encode($recentTransactionsChart),
			'incomingTransactions' => $incomingTransactions,
		]);
	}

	/**
	 * @param Budget $budget
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionShowRecentTransactions(Budget $budget, Request $request) {
		$filterCount = $request->get('count', 50);

		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budget.show-recent-transactions', $budget->id), __('Finances::breadcrumb.budget.show-recent-transactions'));

		$this->transactionHistoryCollectorService
			->reset()
			->setParentType(Transaction::PARENT_TYPE_BUDGET)
			->setParentId($budget->id)
			->setEndDate(new Carbon('now'))
			->setSortDirection('asc');

		$transactions =
			$this->transactionHistoryCollectorService
				->getRows()
				->reverse()
				->take($filterCount);

		return view('Finances::budget/show-recent-transactions', [
			'budget' => $budget,
			'transactions' => $transactions,
			'filterCount' => $filterCount,
		]);
	}

}