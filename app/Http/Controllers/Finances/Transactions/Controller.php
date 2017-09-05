<?php

namespace App\Http\Controllers\Finances\Transactions;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Model;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Services\Breadcrumb\ManagerContract as BreadcrumbManagerContract;

abstract class Controller
	extends BaseController {

	/**
	 * @var BreadcrumbManagerContract
	 */
	protected $breadcrumbManager;

	/**
	 * @var TransactionRepositoryContract
	 */
	protected $transactionRepository;

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @param BreadcrumbManagerContract $breadcrumbManager
	 * @param TransactionRepositoryContract $transactionRepository
	 * @param TransactionCategoryRepositoryContract $transactionCategoryRepository
	 */
	public function __construct(
		BreadcrumbManagerContract $breadcrumbManager,
		TransactionRepositoryContract $transactionRepository,
		TransactionCategoryRepositoryContract $transactionCategoryRepository
	) {
		$this->breadcrumbManager = $breadcrumbManager;
		$this->transactionRepository = $transactionRepository;
		$this->transactionCategoryRepository = $transactionCategoryRepository;
	}

	/**
	 * @param string $viewName
	 * @param Model $transactionParent
	 * @param string $transactionParentType
	 * @return mixed
	 */
	protected function getCreateView(string $viewName, Model $transactionParent, string $transactionParentType) {
		return $this->getCreateEditView(sprintf('views.finances.transactions.create.%s', $viewName), null, $transactionParent, $transactionParentType);
	}

	/**
	 * Returns appropriate create/edit view with common data filled.
	 * @param string $viewName
	 * @param Transaction|null $transaction
	 * @param Model $transactionParent
	 * @param string $transactionParentType
	 * @return mixed
	 */
	private function getCreateEditView(string $viewName, ?Transaction $transaction, Model $transactionParent, string $transactionParentType) {
		$categories = $this->getCategories();

		return view($viewName, [
			'transaction' => $transaction,
			'transactionParent' => $transactionParent,
			'transactionParentType' => $transactionParentType,
			'categories' => $categories,
		]);
	}

	/**
	 * @return array
	 */
	protected function getCategories(): array {
		$categories = $this->transactionCategoryRepository->getAll();
		$categories = $this->transactionCategoryRepository->resolveFullNames($categories);
		$categories = $categories->sortBy('full_name');

		$result = [];

		foreach ($categories as $category) {
			/**
			 * @var \App\Models\TransactionCategory $category
			 */

			$categoryPresenter = $category->getPresenter();
			$result[$category->id] = $categoryPresenter->getFullName();
		}

		return $result;
	}

	/**
	 * @param Transaction $transaction
	 * @param Model $transactionParent
	 * @param string $transactionParentType
	 * @return mixed
	 */
	protected function getEditView(Transaction $transaction, Model $transactionParent, string $transactionParentType) {
		if (isset($transaction->parent_transaction_id)) {
			$parentTransaction = $this->transactionRepository->getOrFail($transaction->parent_transaction_id);
			$this->breadcrumbManager->push($parentTransaction);
		}

		$this->breadcrumbManager
			->pushUrl(route('finances.transactions.edit', $transaction->id), __('breadcrumbs.transactions.edit', [
				'transactionName' => $transaction->name,
			]));

		return $this->getCreateEditView('views.finances.transactions.edit', $transaction, $transactionParent, $transactionParentType);
	}

}