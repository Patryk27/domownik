<?php

namespace App\Services\Transaction\Category\RequestManager;

use App\Exceptions\Exception;
use App\Models\TransactionCategory;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;
use Illuminate\Support\Collection;

class CategoryDeleter {

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @var Collection|TransactionCategory[]
	 */
	protected $categories;

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
	public function deleteCategories(array $categoryIds): self {
		$this->categories =
			$this->transactionCategoryRepository
				->getAll(['id', 'parent_category_id'])
				->keyBy('id');

		foreach ($categoryIds as $categoryId) {
			$this->deleteCategoryById($categoryId);
		}

		return $this;
	}

	/**
	 * @param int $categoryId
	 * @return CategoryDeleter
	 */
	protected function deleteCategoryById(int $categoryId): self {
		if (!isset($this->categories[$categoryId])) {
			throw new Exception('Could not find category with id=%d.', $categoryId);
		}

		// remove category children first
		foreach ($this->categories as $childCategory) {
			if ($childCategory->parent_category_id === $categoryId) {
				$this->deleteCategoryById($childCategory->id);
			}
		}

		// we can safely remove the category itself
		$this->transactionCategoryRepository->delete($categoryId);

		return $this;
	}

}