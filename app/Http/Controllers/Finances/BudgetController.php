<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Budget\Transaction\SearchBooked as SearchBookedTransactionRequest;
use App\Http\Requests\Budget\Transaction\SearchScheduled as SearchScheduledTransactionRequest;
use App\Models\Budget;
use App\Models\Transaction;
use App\Repositories\Contracts\BudgetRepositoryContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\Services\Search\Transaction\OneShotSearchContract as OneShotTransactionSearchContract;
use App\Services\Search\Transaction\ScheduleSearchContract as TransactionScheduleSearchContract;
use Carbon\Carbon;

class BudgetController
	extends BaseController {

	/**
	 * @var BreadcrumbManager
	 */
	protected $breadcrumbManager;

	/**
	 * @var BudgetRepositoryContract
	 */
	protected $budgetRepository;

	/**
	 * @var OneShotTransactionSearchContract
	 */
	protected $oneShotTransactionSearch;

	/**
	 * @var TransactionScheduleSearchContract
	 */
	protected $transactionScheduleSearch;

	/**
	 * @param BreadcrumbManager $breadcrumbManager
	 * @param BudgetRepositoryContract $budgetRepository
	 * @param OneShotTransactionSearchContract $oneShotTransactionSearch
	 * @param TransactionScheduleSearchContract $transactionScheduleSearch
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		BudgetRepositoryContract $budgetRepository,
		OneShotTransactionSearchContract $oneShotTransactionSearch,
		TransactionScheduleSearchContract $transactionScheduleSearch
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->budgetRepository = $budgetRepository;
		$this->oneShotTransactionSearch = $oneShotTransactionSearch;
		$this->transactionScheduleSearch = $transactionScheduleSearch;
	}

	/**
	 * @return mixed
	 */
	public function index() {
		$this->breadcrumbManager->push(route('finances.budgets.index'), __('breadcrumbs.budgets.index'));

		$budgets = $this->budgetRepository->getActiveBudgets();

		return view('views.finances.budgets.index', [
			'budgets' => $budgets,
		]);
	}

	/**
	 * @param Budget $budget
	 * @return mixed
	 */
	public function show(Budget $budget) {
		$this->breadcrumbManager->pushCustom($budget);

		// get recently booked transactions
		$this->oneShotTransactionSearch
			->parent(Transaction::PARENT_TYPE_BUDGET, $budget->id)
			->date('<=', new Carbon());

		$this->oneShotTransactionSearch
			->getQueryBuilder()
			->orderBy(OneShotTransactionSearchContract::ORDER_DATE, 'desc');

		$recentTransactionsChart = $this->oneShotTransactionSearch->getChart();

		$this->oneShotTransactionSearch
			->getQueryBuilder()
			->limit(5);

		$recentTransactions = $this->oneShotTransactionSearch->get();

		// get incoming transactions
		$this->transactionScheduleSearch
			->parent(Transaction::PARENT_TYPE_BUDGET, $budget->id)
			->date('>=', new Carbon());

		$this->transactionScheduleSearch
			->getQueryBuilder()
			->orderBy(TransactionScheduleSearchContract::ORDER_DATE, 'asc')
			->orderBy(TransactionScheduleSearchContract::TRANSACTION_ID, 'asc')
			->limit(5);

		$incomingTransactions =
			$this->transactionScheduleSearch
				->get()
				->reverse();

		return view('views.finances.budgets.show', [
			'budget' => $budget,
			'recentTransactions' => $recentTransactions,
			'recentTransactionsChart' => $recentTransactionsChart,
			'incomingTransactions' => $incomingTransactions,
		]);
	}

	/**
	 * @param Budget $budget
	 * @param SearchBookedTransactionRequest $request
	 * @return mixed
	 */
	public function bookedTransactions(Budget $budget, SearchBookedTransactionRequest $request) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budgets.booked-transactions', $budget->id), __('breadcrumbs.budgets.booked-transactions'));

		$this->oneShotTransactionSearch->parent(Transaction::PARENT_TYPE_BUDGET, $budget->id);

		// apply filters
		if ($request->has('dateFrom')) {
			$this->oneShotTransactionSearch->date('>=', new Carbon($request->get('dateFrom')));
		}

		if ($request->has('dateTo')) {
			$this->oneShotTransactionSearch->date('<=', new Carbon($request->get('dateTo')));
		} else {
			$this->oneShotTransactionSearch->date('<=', new Carbon());
		}

		if ($request->has('name')) {
			$this->oneShotTransactionSearch->name($request->get('name'));
		}

		// apply limit
		$this->oneShotTransactionSearch
			->getQueryBuilder()
			->orderBy(OneShotTransactionSearchContract::ORDER_DATE, 'desc')
			->limit($request->get('limit', 100));

		// fetch data
		$transactions = $this->oneShotTransactionSearch->get();

		return view('views.finances.budgets.transactions', [
			'budget' => $budget,
			'transactions' => $transactions,
		]);
	}

	/**
	 * @param Budget $budget
	 * @param SearchScheduledTransactionRequest $request
	 * @return mixed
	 */
	public function scheduledTransactions(Budget $budget, SearchScheduledTransactionRequest $request) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.budgets.scheduled-transactions', $budget->id), __('breadcrumbs.budgets.scheduled-transactions'));

		$this->transactionScheduleSearch->parent(Transaction::PARENT_TYPE_BUDGET, $budget->id);

		// apply filters
		if ($request->has('dateFrom')) {
			$this->transactionScheduleSearch->date('>=', new Carbon($request->get('dateFrom')));
		} else {
			$this->transactionScheduleSearch->date('>=', new Carbon());
		}

		if ($request->has('dateTo')) {
			$this->transactionScheduleSearch->date('<=', new Carbon($request->get('dateTo')));
		}

		if ($request->has('name')) {
			$this->transactionScheduleSearch->name($request->get('name'));
		}

		// apply limit
		$this->transactionScheduleSearch
			->getQueryBuilder()
			->orderBy(TransactionScheduleSearchContract::ORDER_DATE, 'asc')
			->orderBy(TransactionScheduleSearchContract::TRANSACTION_ID, 'asc')
			->limit($request->get('limit', 100));

		$transactions = $this->transactionScheduleSearch->get();

		return view('views.finances.budgets.transactions', [
			'budget' => $budget,
			'transactions' => $transactions,
		]);
	}

}