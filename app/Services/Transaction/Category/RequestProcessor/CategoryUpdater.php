<?php

namespace App\Services\Transaction\Category\RequestProcessor;

use App\Exceptions\Exception;
use App\Models\TransactionCategory;

class CategoryUpdater {

	/**
	 * @var array
	 */
	protected $categories;

	/**
	 * @param array $rawCategories
	 * @return $this
	 */
	public function updateCategories(array $rawCategories): self {
		$this->buildCategoryList($rawCategories);

		foreach (array_keys($this->categories) as $categoryId) {
			$this->saveCategory($categoryId);
		}

		return $this;
	}

	/**
	 * @param array $rawCategories
	 * @return $this
	 */
	protected function buildCategoryList(array $rawCategories): self {
		$this->categories = [];

		foreach ($rawCategories as $rawCategory) {
			$categoryId = $rawCategory['id'];

			$this->categories[$categoryId] = [
				'id' => $categoryId,
				'name' => $rawCategory['text'],
				'parent_id' => $rawCategory['parent'] === '#' ? null : $rawCategory['parent'],
				'meta' => [
					'is_saved' => false,
				],
			];
		}

		return $this;
	}

	/**
	 * Saves given category to the database, if required, and returns its id.
	 * @param string $categoryId
	 * @return int
	 * @throws Exception
	 */
	protected function saveCategory(string $categoryId): int {
		if (!isset($this->categories[$categoryId])) {
			throw new Exception('Category with id=%s has not been found.', $categoryId);
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

		$transactionCategory->saveOrFail();

		// update meta data
		$category['id'] = $transactionCategory->id;
		$category['meta']['is_saved'] = true;

		$this->categories[$categoryId] = $category;

		return $transactionCategory->id;
	}

}