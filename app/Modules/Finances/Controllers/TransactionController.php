<?php

namespace App\Modules\Finances\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Finances\Http\Requests\Transaction\StoreRequest;
use App\Modules\Finances\Models\Budget;
use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Repositories\Contracts\BudgetRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionPeriodicityRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Services\Transaction\RequestManagerContract;
use App\Modules\Finances\Services\TransactionSchedule\UpdaterContract;
use App\Services\Breadcrumb\Manager as BreadcrumbManager;
use App\ServiceContracts\RequestManagerContract as BaseRequestManagerContract;

class TransactionController
	extends Controller {

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
	 * @return \Illuminate\Http\Response
	 */
	public function actionCreateToBudget(Budget $budget) {
		$this->breadcrumbManager
			->pushCustom($budget)
			->push(route('finances.transaction.create-to-budget', $budget->id), __('Finances::breadcrumb.transaction.create'));

		$view = $this->getCreateEditView('create-to-budget');
		$view->with([
			'budget' => $budget,
		]);

		return $view;
	}

	/**
	 * @param Transaction $transaction
	 * @return \Illuminate\Http\Response
	 */
	public function actionEdit(Transaction $transaction) {
		$this->pushTransactionParentBreadcrumb($transaction);

		$this->breadcrumbManager->push(route('finances.transaction.edit', $transaction->id), __('Finances::breadcrumb.transaction.edit', [
			'transactionName' => $transaction->name
		]));

		$view = $this->getCreateEditView('edit');
		$view->with([
			'transaction' => $transaction
		]);

		return $view;
	}

	/**
	 * @param StoreRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function actionStore(StoreRequest $request) {
		$storeResult = $this->transactionRequestManagerService->store($request);
		$transaction = $this->transactionRequestManagerService->getModel();

		$this->transactionScheduleUpdaterService->updateScheduleByTransactionId($transaction->id);

		switch ($storeResult) {
			case BaseRequestManagerContract::STORE_RESULT_CREATED:
				flash()->success(__('Finances::requests/transaction/store.messages.create-success'));
				$redirectUrl = $this->getTransactionParentUrl($transaction);
				break;

			case BaseRequestManagerContract::STORE_RESULT_UPDATED:
				flash()->success(__('Finances::requests/transaction/store.messages.update-success'));
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
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function actionDelete(Transaction $transaction) {
		$this->transactionRequestManagerService->delete($transaction->id);

		flash()->success(__('Finances::requests/transaction/delete.messages.delete-success'));

		return response()->json([
			'redirectUrl' => $this->getTransactionParentUrl($transaction),
		]);
	}

	/**
	 * @param Budget $budget
	 * @return \Illuminate\Http\Response
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
		$this->transactionCategoryRepository->resolveFullNames($categories);

		return View('Finances::transaction.' . $viewName, [
			'categories' => $categories,
			'transaction' => null,
			'budget' => null,
		]);
	}

	/**
	 * @param Transaction $transaction
	 * @return string
	 */
	protected function getTransactionParentUrl(Transaction $transaction) {
		switch ($transaction->parent_type) {
			case Transaction::PARENT_TYPE_BUDGET:
				return route('finances.budget.show', $transaction->parent_id);

			case Transaction::PARENT_TYPE_SAVING:
				return route('finances.saving.show', $transaction->parent_id);
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