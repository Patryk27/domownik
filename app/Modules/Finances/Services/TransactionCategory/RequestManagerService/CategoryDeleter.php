<?php

namespace App\Modules\Finances\Services\TransactionCategory\RequestManagerService;

use App\Exceptions\Exception;
use App\Modules\Finances\Models\TransactionCategory;
use App\Modules\Finances\Repositories\Contracts\TransactionCategoryRepositoryContract;
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
	 * RequestManagerService constructor.
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