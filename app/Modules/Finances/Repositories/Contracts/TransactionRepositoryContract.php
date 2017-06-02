<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\Models\Transaction;
use App\Repositories\Contracts\CrudRepositoryContract;
use Illuminate\Support\Collection;

/**
 * @method Transaction get(int $id, array $columns = ['*'])
 * @method Transaction getOrFail(int $id, array $columns = ['*'])
 * @method Collection|Transaction[] getBy(string $fieldName, $fieldValue, array $columns = ['*'])
 * @method Collection|Transaction[] getAll(array $columns = ['*'])
 */
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

}