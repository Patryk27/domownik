<?php

namespace App\Http\Controllers\Finances\Transaction;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Model;
use App\Models\Transaction;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;

class Controller
	extends BaseController {

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @param TransactionCategoryRepositoryContract $transactionCategoryRepository
	 */
	public function __construct(
		TransactionCategoryRepositoryContract $transactionCategoryRepository
	) {
		$this->transactionCategoryRepository = $transactionCategoryRepository;
	}

	/**
	 * Returns appropriate create/edit view with common data filled.
	 * @param string $viewName
	 * @param Transaction|null $transaction
	 * @param Model $transactionParent
	 * @param string $transactionParentType
	 * @return mixed
	 */
	protected function getCreateEditView(string $viewName, ?Transaction $transaction, Model $transactionParent, string $transactionParentType) {
		$categories = $this->getCategories();

		return view(sprintf('views.finances.transactions.create-edit.%s', $viewName), [
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

}