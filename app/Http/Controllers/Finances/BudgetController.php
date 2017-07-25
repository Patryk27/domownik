<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller as BaseController;
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

		// get recent transactions
		$this->oneShotTransactionSearch
			->parentTypeAndId(Transaction::PARENT_TYPE_BUDGET, $budget->id)
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
			->parentTypeAndId(Transaction::PARENT_TYPE_BUDGET, $budget->id)
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

}