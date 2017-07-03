<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\Models\Transaction;
use App\Repositories\Contracts\CrudRepositoryContract;
use Illuminate\Support\Collection;

interface TransactionRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * Returns all transactions linked to given budget.
	 * @param int $budgetId
	 * @return Collection|Transaction[]
	 */
	public function getByBudgetId(int $budgetId): Collection;

	/**
	 * Returns all transactions linked to given category.
	 * @param int $transactionCategoryId
	 * @return Collection|Transaction[]
	 */
	public function getByCategoryId(int $transactionCategoryId): Collection;

	#region Inherited from CrudRepositoryContract

	/**
	 * @inheritdoc
	 * @return Transaction|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Transaction
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Collection|Transaction[]
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @inheritdoc
	 * @return Collection|Transaction[]
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	#endregion

}