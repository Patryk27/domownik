<?php

namespace App\Modules\Finances\Controllers;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Collection;

class BudgetController
	extends Controller {

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
			->setDateTo(new Carbon('now'))
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
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budget.show-recent-transactions', $budget->id), __('Finances::breadcrumb.budget.show-recent-transactions'));

		// load request data
		$dateFrom = $request->get('dateFrom');
		$dateTo = $request->get('dateTo');
		$count = $request->get('count');

		// prepare date range
		if (empty($dateFrom)) {
			$dateFrom = null;
		} else {
			$dateFrom = new Carbon($dateFrom);
		}

		if (empty($dateTo)) {
			$dateTo = new Carbon('now');
		} else {
			$dateTo = new Carbon($dateTo);
		}

		// check if date range is valid
		if ($dateTo < $dateFrom) {
			$transactions = new Collection();
			flash()->warning(__('Finances::views/budget/show-recent-transactions.messages.date-to-is-before-from'));
		} else {
			$this->transactionHistoryCollectorService
				->reset()
				->setParentType(Transaction::PARENT_TYPE_BUDGET)
				->setParentId($budget->id)
				->setDateFrom($dateFrom)
				->setDateTo($dateTo)
				->setSortDirection('asc');

			$transactions =
				$this->transactionHistoryCollectorService
					->getRows()
					->reverse();

			if (isset($count)) {
				$transactions = $transactions->take($count);
			}
		}

		return view('Finances::budget/show-recent-transactions', [
			'budget' => $budget,
			'transactions' => $transactions,
			'dateFrom' => isset($dateFrom) ? $dateFrom->format('Y-m-d') : '', // @todo format should not be hardcoded
			'dateTo' => $dateTo->format('Y-m-d'), // @todo format should not be hardcoded
			'count' => $count,
		]);
	}

	/**
	 * @param Budget $budget
	 * @param Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionShowIncomingTransactions(Budget $budget, Request $request) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budget.show-incoming-transactions', $budget->id), __('Finances::breadcrumb.budget.show-incoming-transactions'));

		// load request data
		$dateFrom = $request->get('dateFrom');
		$dateTo = $request->get('dateTo');

		// prepare date range
		if (empty($dateFrom)) {
			$dateFrom = Date::stripTime(new Carbon('now'));
		} else {
			$dateFrom = new Carbon($dateFrom);
		}

		if (empty($dateTo)) {
			$dateTo =
				$dateFrom
					->copy()
					->addMonth();
		} else {
			$dateTo = new Carbon($dateTo);
		}

		// check if date range is valid
		if ($dateTo < $dateFrom) {
			$transactions = new Collection();
			flash()->warning(__('Finances::views/budget/show-incoming-transactions.messages.date-to-is-before-from'));
		} else {
			$transactions = $this->transactionScheduleRepository->getByBudgetId($budget->id, $dateFrom, $dateTo);
		}

		return view('Finances::budget/show-incoming-transactions', [
			'budget' => $budget,
			'transactions' => $transactions,
			'dateFrom' => $dateFrom->format('Y-m-d'), // @todo format should not be hardcoded
			'dateTo' => $dateTo->format('Y-m-d'), // @todo format should not be hardcoded
		]);
	}

}