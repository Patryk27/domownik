<?php

namespace App\Services\Transaction\Category\RequestProcessor;

use App\Repositories\Contracts\TransactionCategoryRepositoryContract;

class CategoryDeleter {

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
	 * @param array $categoryIds
	 * @return $this
	 */
	public function deleteCategories(array $categoryIds) {
		foreach ($categoryIds as $categoryId) {
			/**
			 * All the categories have 'on delete cascade' foreign keys so we
			 * don't have to worry about deleting the children.
			 */

			$this->transactionCategoryRepository->delete($categoryId);
		}

		return $this;
	}

}