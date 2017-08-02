<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Budget\Crud\StoreRequest as BudgetStoreRequest;
use App\Models\Budget;
use App\Models\Transaction;
use App\Repositories\Contracts\BudgetRepositoryContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\Services\Budget\RequestProcessorContract as BudgetRequestProcessorContract;
use App\Services\Search\Transaction\OneShotSearchContract as OneShotTransactionSearchContract;
use App\Services\Search\Transaction\ScheduleSearchContract as TransactionScheduleSearchContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
	 * @var BudgetRequestProcessorContract
	 */
	protected $budgetRequestProcessor;

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
	 * @param BudgetRequestProcessorContract $budgetRequestProcessor
	 * @param OneShotTransactionSearchContract $oneShotTransactionSearch
	 * @param TransactionScheduleSearchContract $transactionScheduleSearch
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		BudgetRepositoryContract $budgetRepository,
		BudgetRequestProcessorContract $budgetRequestProcessor,
		OneShotTransactionSearchContract $oneShotTransactionSearch,
		TransactionScheduleSearchContract $transactionScheduleSearch
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->budgetRepository = $budgetRepository;
		$this->budgetRequestProcessor = $budgetRequestProcessor;
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
	 * @return mixed
	 */
	public function create() {
		$this->breadcrumbManager
			->push(route('finances.budgets.index'), __('breadcrumbs.budgets.index'))
			->push(route('finances.budgets.create'), __('breadcrumbs.budgets.create'));

		return view('views.finances.budgets.create', [
			'form' => [
				'url' => route('finances.budgets.store'),
				'method' => 'post',
			],

			'budgetsSelect' => $this->getBudgetsSelect(),
		]);
	}

	/**
	 * @param Budget $budget
	 * @return mixed
	 */
	public function edit(Budget $budget) {
		$this->breadcrumbManager
			->push(route('finances.budgets.index'), __('breadcrumbs.budgets.index'))
			->push(route('finances.budgets.edit', $budget->id), __('breadcrumbs.budgets.edit'));

		return view('views.finances.budgets.edit', [
			'form' => [
				'url' => route('finances.budgets.update', $budget->id),
				'method' => 'put',
			],

			'budget' => $budget,
			'budgetsSelect' => $this->getBudgetsSelect(),
		]);
	}

	/**
	 * @param BudgetStoreRequest $request
	 * @return mixed
	 */
	public function store(BudgetStoreRequest $request) {
		$result = $this->budgetRequestProcessor->store($request);
		$budget = $result->getBudget();

		$this->flash('success', __('requests/budget/crud.messages.stored'));

		return response()->json([
			'redirectUrl' => route('finances.budgets.edit', $budget->id),
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
	 * @return Collection
	 */
	protected function getBudgetsSelect(): Collection {
		return
			$this->budgetRepository
				->getActiveBudgets()
				->mapWithKeys(function(Budget $budget) {
					return [
						$budget->id => $budget->name,
					];
				});
	}

}