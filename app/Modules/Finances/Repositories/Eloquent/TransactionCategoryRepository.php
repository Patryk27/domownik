<?php

namespace App\Modules\Finances\Repositories\Eloquent;

use App\Modules\Finances\Models\TransactionCategory;
use App\Modules\Finances\Repositories\Contracts\TransactionCategoryRepositoryContract;
use App\Repositories\Eloquent\AbstractCrudRepository;
use Illuminate\Support\Collection;

/**
 * Class TransactionCategoryRepository
 * @package App\Modules\Finances\Repositories\Eloquent
 */
class TransactionCategoryRepository
	extends AbstractCrudRepository
	implements TransactionCategoryRepositoryContract {

	/**
	 * Category name separator used when resolving full category name.
	 * That is: when eg. getFullName() is called, this separator is put between the next category names.
	 * Example: in 'Hello -> World' the category name separator is ' -> '.
	 */
	const CATEGORY_NAME_SEPARATOR = ' → ';

	/**
	 * @inheritDoc
	 */
	public function getMainCategories(): Collection {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() {
			return TransactionCategory
				::whereNull('parent_category_id')
				->orderBy('name')
				->get();
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getSubcategories(int $parentId): Collection {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($parentId) {
			return TransactionCategory
				::where('parent_category_id', $parentId)
				->orderBy('name')
				->get();
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getFullName(int $categoryId): string {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() {
			$result = [];

			while (!empty($categoryId)) {
				/**
				 * @var TransactionCategory $category
				 */
				$category = $this->get($categoryId);
				$categoryId = $category->parent_category_id;

				$result[] = $category->name;
			}

			return implode(self::CATEGORY_NAME_SEPARATOR, array_reverse($result));
		});
	}

	/**
	 * @inheritDoc
	 */
	public function resolveFullNames(Collection $categories): Collection {
		/**
		 * @var Collection|TransactionCategory[] $categories
		 * @var Collection|TransactionCategory[] $categoryMap
		 */

		$categoryMap = $categories->keyBy('id');

		$cache = $this->getCache();

		/**
		 * @var Callable $getFullName
		 */
		$getFullName = null;
		$getFullName = function($categoryId) use (&$getFullName, &$categoryMap, &$cache) {
			if (is_null($categoryId)) {
				return [];
			}

			$cacheKey = $this->getCacheKey(__FUNCTION__ . ':getFullName', func_get_args());

			return $cache->rememberForever($cacheKey, function() use (&$getFullName, &$categoryMap, $categoryId) {
				if (!$categoryMap->has($categoryId)) {
					$categoryMap[$categoryId] = $this->getOrFail($categoryId);
				}

				$category = $categoryMap[$categoryId];

				return array_merge($getFullName($category->parent_category_id), [$category->name]);
			});
		};

		$result = new Collection();

		foreach ($categories as $category) {
			$category = clone $category;
			$category->full_name = implode(self::CATEGORY_NAME_SEPARATOR, $getFullName($category->id));

			$result->push($category);
		}

		return $result;
	}

	/**
	 * @inheritdoc
	 */
	protected function getModelName(): string {
		return TransactionCategory::class;
	}

}