<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\Models\Budget;
use App\Repositories\Contracts\CrudRepositoryContract;
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