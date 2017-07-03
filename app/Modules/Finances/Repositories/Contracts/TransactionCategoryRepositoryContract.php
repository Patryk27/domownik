<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\Models\TransactionCategory;
use App\Repositories\Contracts\CrudRepositoryContract;
use Illuminate\Support\Collection;

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
	public function resolveFullNames(Collection $categories): Collection;

	#region Inherited from CrudRepositoryContract

	/**
	 * @inheritdoc
	 * @return TransactionCategory|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return TransactionCategory
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Collection|TransactionCategory[]
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @inheritdoc
	 * @return Collection|TransactionCategory[]
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	#endregion

}