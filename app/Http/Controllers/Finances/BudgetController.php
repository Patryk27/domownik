<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Budget\StoreRequest as BudgetStoreRequest;
use App\Models\Budget;
use App\Models\Transaction;
use App\Repositories\Contracts\BudgetRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\Services\Budget\RequestManagerContract as BudgetRequestManagerContract;
use App\Services\Transaction\HistoryCollectorContract;
use App\Support\Facades\Date;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BudgetController
	extends BaseController {

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
	 * @return mixed
	 */
	public function actionCreate() {
		$this->breadcrumbManager->push(route('finances.budget.create'), __('breadcrumbs.budget.create'));

		return view('views.finances.budget.create', [
			'budgetTypes' => Budget::getTypes(),
			'activeBudgets' => $this->budgetRepository->getActiveBudgets(),
		]);
	}

	/**
	 * @param BudgetStoreRequest $request
	 * @return mixed
	 */
	public function actionStore(BudgetStoreRequest $request) {
		$this->budgetRequestManager->store($request);

		$budget = $this->budgetRequestManager->getBudget();

		flash(__('requests/budget/store.messages.success', [
			'budgetName' => $budget->name,
		]), 'success');

		return response()->json([
			'redirectUrl' => route('finances.budget.show', $budget->id),
		]);
	}

	/**
	 * @param Budget $budget
	 * @return mixed
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

		return view('views.finances.budget.show', [
			'budget' => $budget,
			'recentTransactions' => $recentTransactions,
			'recentTransactionsChart' => json_encode($recentTransactionsChart),
			'incomingTransactions' => $incomingTransactions,
		]);
	}

	/**
	 * @return mixed
	 */
	public function actionList() {
		$this->breadcrumbManager->push(route('finances.budget.list'), __('breadcrumbs.budget.list'));

		$budgets = $this->budgetRepository->getActiveBudgets();

		return view('views.finances.budget.list', [
			'budgets' => $budgets,
		]);
	}

	/**
	 * @param Budget $budget
	 * @param Request $request
	 * @return mixed
	 */
	public function actionShowRecentTransactions(Budget $budget, Request $request) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budget.show-recent-transactions', $budget->id), __('breadcrumbs.budget.show-recent-transactions'));

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
			flash()->warning(__('views/finances/budget/show-recent-transactions.messages.date-to-is-before-from'));
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

		return view('views.finances.budget.show-recent-transactions', [
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
	 * @return mixed
	 */
	public function actionShowIncomingTransactions(Budget $budget, Request $request) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budget.show-incoming-transactions', $budget->id), __('breadcrumbs.budget.show-incoming-transactions'));

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
			flash()->warning(__('views/finances/budget/show-incoming-transactions.messages.date-to-is-before-from'));
		} else {
			$transactions = $this->transactionScheduleRepository->getByBudgetId($budget->id, $dateFrom, $dateTo);
		}

		return view('views.finances.budget.show-incoming-transactions', [
			'budget' => $budget,
			'transactions' => $transactions,
			'dateFrom' => $dateFrom->format('Y-m-d'), // @todo format should not be hardcoded
			'dateTo' => $dateTo->format('Y-m-d'), // @todo format should not be hardcoded
		]);
	}

}