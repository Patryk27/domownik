<?php

namespace App\Services\Transaction\Category\RequestProcessor;

use App\Exceptions\Exception;
use App\Models\TransactionCategory;
use App\Repositories\Contracts\TransactionCategoryRepositoryContract;

class CategoryUpdater {

	/**
	 * @var TransactionCategoryRepositoryContract
	 */
	protected $transactionCategoryRepository;

	/**
	 * @var array
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
	 * @param array $categories
	 * @return $this
	 */
	public function updateCategories(array $categories) {
		$this->buildCategoryList($categories);

		foreach (array_keys($this->categories) as $categoryId) {
			$this->saveCategory($categoryId);
		}

		return $this;
	}

	/**
	 * @param array $categories
	 * @return $this
	 */
	protected function buildCategoryList(array $categories) {
		$this->categories = [];

		foreach ($categories as $category) {
			$categoryId = $category['id'];

			$this->categories[$categoryId] = [
				'id' => $categoryId,
				'name' => $category['text'],
				'parent_id' => $category['parent'] === '#' ? null : $category['parent'],

				'meta' => [
					'is_saved' => false,
				],
			];
		}

		return $this;
	}

	/**
	 * Saves given category to the database and returns its id.
	 * Notice that $categoryId is deliberately a string because jsTree creates ids
	 * like "1_2_3" which we must parse.
	 * @param string $categoryId
	 * @return int
	 * @throws Exception
	 */
	protected function saveCategory(string $categoryId): int {
		if (!isset($this->categories[$categoryId])) {
			throw new Exception('Category [id=%s] was not found.', $categoryId);
		}

		$category = $this->categories[$categoryId];

		// do not save categories multiple times
		if ($category['meta']['is_saved']) {
			return $category['id'];
		}

		// the parent has to be saved first, so that we can have its id
		if (isset($category['parent_id'])) {
			$parentCategoryId = $this->saveCategory($category['parent_id']);
		} else {
			$parentCategoryId = null;
		}

		// prepare the transaction category model
		$transactionCategory = new TransactionCategory([
			'name' => $category['name'],
			'parent_category_id' => $parentCategoryId,
		]);

		if (ctype_digit($categoryId)) {
			$transactionCategory->id = (int)$categoryId;
			$transactionCategory->exists = true;
		}

		$this->transactionCategoryRepository->persist($transactionCategory);

		// update metadata
		$category['id'] = $transactionCategory->id;
		$category['meta']['is_saved'] = true;

		$this->categories[$categoryId] = $category;

		return $transactionCategory->id;
	}

}