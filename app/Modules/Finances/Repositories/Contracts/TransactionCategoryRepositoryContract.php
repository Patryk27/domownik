<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\Models\TransactionCategory;
use App\Repositories\Contracts\CrudRepositoryContract;
use Illuminate\Support\Collection;

/**
 * @method TransactionCategory get(int $id, array $columns = ['*'])
 * @method TransactionCategory getOrFail(int $id, array $columns = ['*'])
 * @method Collection|TransactionCategory[] getBy(string $fieldName, $fieldValue, array $columns = ['*'])
 * @method Collection|TransactionCategory[] getAll(array $columns = ['*'])
 */
interface TransactionCategoryRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * Returns categories that does not have any parent.
	 * @return Collection|TransactionCategory[]
	 */
	public function getMainCategories(): Collection;

	/**
	 * Returns children categories of given category.
	 * @param int $parentId
	 * @return Collection|TransactionCategory[]
	 */
	public function getSubcategories(int $parentId): Collection;

	/**
	 * Returns category full name.
	 * That is its parents name and itselves.
	 * @param int $categoryId
	 * @return string
	 */
	public function getFullName(int $categoryId): string;

	/**
	 * Resolves full name of every category in given collection.
	 * @param Collection|TransactionCategory[] $categories
	 * @return Collection|TransactionCategory[]
	 */
	public function resolveFullNames(Collection $categories): TransactionCategoryRepositoryContract;

}