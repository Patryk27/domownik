<?php

namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Transaction\StoreRequest;
use App\Models\Budget;
use App\Models\Transaction;
use App\Repositories\Contracts\BudgetRepositoryContract;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\ServiceContracts\RequestManagerContract as BaseRequestManagerContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\Services\Transaction\RequestManagerContract;
use App\Services\Transaction\Schedule\UpdaterContract;

class TransactionController
	extends BaseController {

	/**
	 * @var BreadcrumbManager
	 */
	protected $breadcrumbManager;

	/**
	 * @var TransactionRepositoryContract
	 */
	protected $transactionRepository;

	/**
	 * @var TransactionPeriodicityRepositoryContract
	 */
	protected $transactionPeriodicityRepository;

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @var RequestManagerContract
	 */
	protected $transactionRequestManagerService;

	/**
	 * @var UpdaterContract
	 */
	protected $transactionScheduleUpdaterService;

	/**
	 * @var BudgetRepositoryContract
	 */
	protected $budgetRepository;

	/**
	 * @param BreadcrumbManager $breadcrumbManager
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository
	 * @param TransactionCategoryRepositoryContract $transactionCategoryRepository
	 * @param RequestManagerContract $transactionRequestManagerService
	 * @param UpdaterContract $transactionScheduleUpdaterService
	 * @param BudgetRepositoryContract $budgetRepository
	 */
	public function __construct(
		BreadcrumbManager $breadcrumbManager,
		TransactionRepositoryContract $transactionRepository,
		TransactionPeriodicityRepositoryContract $transactionPeriodicityRepository,
		TransactionCategoryRepositoryContract $transactionCategoryRepository,
		RequestManagerContract $transactionRequestManagerService,
		UpdaterContract $transactionScheduleUpdaterService,
		BudgetRepositoryContract $budgetRepository
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->transactionRepository = $transactionRepository;
		$this->transactionPeriodicityRepository = $transactionPeriodicityRepository;
		$this->transactionCategoryRepository = $transactionCategoryRepository;
		$this->transactionRequestManagerService = $transactionRequestManagerService;
		$this->transactionScheduleUpdaterService = $transactionScheduleUpdaterService;
		$this->budgetRepository = $budgetRepository;
	}

	/**
	 * @param Budget $budget
	 * @return mixed
	 */
	public function actionCreateToBudget(Budget $budget) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.transaction.create-to-budget', $budget->id), __('breadcrumbs.transaction.create'));

		$view = $this->getCreateEditView('create-to-budget');
		$view->with([
			'budget' => $budget,
		]);

		return $view;
	}

	/**
	 * @param Transaction $transaction
	 * @return mixed
	 */
	public function actionEdit(Transaction $transaction) {
		$this->pushTransactionParentBreadcrumb($transaction);

		$this->breadcrumbManager->push(route('finances.transaction.edit', $transaction->id), __('breadcrumbs.transaction.edit', [
			'transactionName' => $transaction->name,
		]));

		$view = $this->getCreateEditView('edit');
		$view->with([
			'transaction' => $transaction,
		]);

		return $view;
	}

	/**
	 * @param StoreRequest $request
	 * @return mixed
	 */
	public function actionStore(StoreRequest $request) {
		$storeResult = $this->transactionRequestManagerService->store($request);
		$transaction = $this->transactionRequestManagerService->getModel();

		$this->transactionScheduleUpdaterService->updateTransactionSchedule($transaction->id);

		switch ($storeResult) {
			case BaseRequestManagerContract::STORE_RESULT_CREATED:
				flash()->success(__('requests/transaction/store.messages.create-success'));
				$redirectUrl = $this->getTransactionParentUrl($transaction);
				break;

			case BaseRequestManagerContract::STORE_RESULT_UPDATED:
				flash()->success(__('requests/transaction/store.messages.update-success'));
				$redirectUrl = route('finances.transaction.edit', $transaction->id);
				break;

			default:
				$redirectUrl = '/';
		}

		return response()->json([
			'redirectUrl' => $redirectUrl,
		]);
	}

	/**
	 * @param Transaction $transaction
	 * @return mixed
	 */
	public function actionDelete(Transaction $transaction) {
		$this->transactionRequestManagerService->delete($transaction->id);
		flash()->success(__('requests/transaction/delete.messages.delete-success'));

		return redirect($this->getTransactionParentUrl($transaction));
	}

	/**
	 * @param Budget $budget
	 * @return mixed
	 */
	public function actionList(Budget $budget) {
		// @todo
	}

	/**
	 * @param string $viewName
	 * @return \Illuminate\View\View
	 */
	protected function getCreateEditView($viewName) {
		$categories = $this->transactionCategoryRepository->getAll();
		$categories = $this->transactionCategoryRepository->resolveFullNames($categories);
		$categories = $categories->sortBy('full_name');

		return View('views.finances.transaction.' . $viewName, [
			'categories' => $categories,
			'transaction' => null,
			'budget' => null,
		]);
	}

	/**
	 * @param Transaction $transaction
	 * @return string
	 */
	protected function getTransactionParentUrl(Transaction $transaction): string {
		switch ($transaction->parent_type) {
			case Transaction::PARENT_TYPE_BUDGET:
				return route('finances.budgets.show', $transaction->parent_id);
		}

		return '/';
	}

	/**
	 * Creates and pushes a breadcrumb of given transaction's parent (a budget or a saving).
	 * @param Transaction $transaction
	 * @return $this
	 */
	protected function pushTransactionParentBreadcrumb(Transaction $transaction) {
		switch ($transaction->parent_type) {
			case Transaction::PARENT_TYPE_BUDGET:
				$budget = $this->budgetRepository->getOrFail($transaction->parent->id);
				$this->breadcrumbManager->pushCustom($budget);
				break;
		}

		return $this;
	}

}