<?php

namespace App\Repositories\Contracts;

use App\Models\Budget;

use Illuminate\Support\Collection;

interface BudgetRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * @return Collection|Budget[]
	 */
	public function getActiveBudgets();

	#region Inherited from CrudRepositoryContract

	/**
	 * @inheritdoc
	 * @return Budget|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Budget
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Collection|Budget[]
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @inheritdoc
	 * @return Collection|Budget[]
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	#endregion

}