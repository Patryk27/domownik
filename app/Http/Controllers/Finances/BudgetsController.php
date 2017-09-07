<?php

namespace App\Http\Controllers\Finances;

use App\Exceptions\UserInterfaceException;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Budget\Crud\StoreRequest as BudgetStoreRequest;
use App\Http\Requests\Budget\Crud\UpdateRequest as BudgetUpdateRequest;
use App\Models\Budget;
use App\Models\Transaction;
use App\Repositories\Contracts\BudgetRepositoryContract;
use App\Services\Breadcrumb\ManagerContract as BreadcrumbManagerContract;
use App\Services\Budget\RequestProcessorContract as BudgetRequestProcessorContract;
use App\Services\Budget\SummaryGeneratorContract as BudgetSummaryGeneratorContract;
use App\Services\Search\Transaction\OneShotSearchContract as OneShotTransactionSearchContract;
use App\Services\Search\Transaction\ScheduleSearchContract as TransactionScheduleSearchContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BudgetsController
	extends BaseController {

	/**
	 * @var BreadcrumbManagerContract
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
	 * @var BudgetSummaryGeneratorContract
	 */
	protected $budgetSummaryGenerator;

	/**
	 * @param BreadcrumbManagerContract $breadcrumbManager
	 * @param BudgetRepositoryContract $budgetRepository
	 * @param BudgetRequestProcessorContract $budgetRequestProcessor
	 * @param OneShotTransactionSearchContract $oneShotTransactionSearch
	 * @param TransactionScheduleSearchContract $transactionScheduleSearch
	 * @param BudgetSummaryGeneratorContract $budgetSummaryGenerator
	 */
	public function __construct(
		BreadcrumbManagerContract $breadcrumbManager,
		BudgetRepositoryContract $budgetRepository,
		BudgetRequestProcessorContract $budgetRequestProcessor,
		OneShotTransactionSearchContract $oneShotTransactionSearch,
		TransactionScheduleSearchContract $transactionScheduleSearch,
		BudgetSummaryGeneratorContract $budgetSummaryGenerator
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->budgetRepository = $budgetRepository;
		$this->budgetRequestProcessor = $budgetRequestProcessor;
		$this->oneShotTransactionSearch = $oneShotTransactionSearch;
		$this->transactionScheduleSearch = $transactionScheduleSearch;
		$this->budgetSummaryGenerator = $budgetSummaryGenerator;
	}

	/**
	 * @return mixed
	 */
	public function index() {
		$this->breadcrumbManager->pushUrl(route('finances.budgets.index'), __('breadcrumbs.budgets.index'));

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
			->pushUrl(route('finances.budgets.index'), __('breadcrumbs.budgets.index'))
			->pushUrl(route('finances.budgets.create'), __('breadcrumbs.budgets.create'));

		return view('views.finances.budgets.create', [
			'form' => [
				'url' => route('finances.budgets.store'),
				'method' => 'post',
			],

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

		$this->putFlash('success', __('requests/budget/crud.messages.stored'));

		return response()->json([
			'redirectUrl' => route('finances.budgets.edit', $budget->id),
		]);
	}

	/**
	 * @param Budget $budget
	 * @return mixed
	 */
	public function show(Budget $budget) {
		$this->breadcrumbManager->push($budget);

		// get recently booked transactions
		$this->oneShotTransactionSearch
			->parent(Transaction::PARENT_TYPE_BUDGET, $budget->id)
			->date('<=', new Carbon());

		$this->oneShotTransactionSearch
			->getQueryBuilder()
			->orderBy(OneShotTransactionSearchContract::TRANSACTION_DATE, 'desc');

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
			->orderBy(TransactionScheduleSearchContract::TRANSACTION_DATE, 'asc')
			->orderBy(TransactionScheduleSearchContract::TRANSACTION_ID, 'asc')
			->limit(5);

		$incomingTransactions = $this->transactionScheduleSearch->get();

		return view('views.finances.budgets.show', [
			'budget' => $budget,
			'recentTransactions' => $recentTransactions,
			'recentTransactionsChart' => $recentTransactionsChart,
			'incomingTransactions' => $incomingTransactions,
		]);
	}

	/**
	 * @param Budget $budget
	 * @return mixed
	 */
	public function edit(Budget $budget) {
		$this->breadcrumbManager
			->pushUrl(route('finances.budgets.show', $budget->id), __('breadcrumbs.budgets.show', [
				'budgetName' => $budget->name,
			]))
			->pushUrl(route('finances.budgets.edit', $budget->id), __('breadcrumbs.budgets.edit', [
				'budgetName' => $budget->name,
			]));

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
	 * @param BudgetUpdateRequest $request
	 * @param int $id
	 * @return mixed
	 */
	public function update(BudgetUpdateRequest $request, int $id) {
		$result = $this->budgetRequestProcessor->update($request, $id);
		$budget = $result->getBudget();

		$this->putFlash('success', __('requests/budget/crud.messages.updated'));

		return response()->json([
			'redirectUrl' => route('finances.budgets.edit', $budget->id),
		]);
	}

	/**
	 * @param int $id
	 * @return mixed
	 */
	public function destroy(int $id) {
		$this->budgetRequestProcessor->delete($id);
		$this->putFlash('success', __('requests/budget/crud.messages.deleted'));

		return response()->json([
			'redirectUrl' => route('finances.budgets.index'),
		]);
	}

	/**
	 * @param Request $request
	 * @param Budget $budget
	 * @return mixed
	 */
	public function summary(Request $request, Budget $budget) {
		$this->breadcrumbManager
			->push($budget)
			->pushUrl(route('finances.budgets.summary', $budget), __('breadcrumbs.budgets.summary'));

		$startingYear = $budget->created_at->year; // @todo - this value should represent furthest possible transaction
		$summary = null;

		try {
			$this->budgetSummaryGenerator
				->setBudgetId($budget->id)
				->setYear($request->get('year', date('Y')))
				->setMonth($request->get('month', date('m')));

			$summary = $this->budgetSummaryGenerator->generateSummary();
		} catch (UserInterfaceException $ex) {
			$this->putMessage('danger', $ex->getMessage());
		}

		return view('views.finances.budgets.summary', [
			'budget' => $budget,
			'startingYear' => $startingYear,
			'summary' => $summary,
		]);
	}

	/**
	 * @return Collection
	 */
	protected function getBudgetsSelect(): Collection {
		return
			$this->budgetRepository
				->getActiveBudgets()
				->mapWithKeys(function (Budget $budget) {
					return [
						$budget->id => $budget->name,
					];
				});
	}

}