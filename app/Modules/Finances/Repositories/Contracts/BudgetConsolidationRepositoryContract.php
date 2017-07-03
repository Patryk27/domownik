<?php

namespace App\Modules\Finances\Repositories\Contracts;

use App\Modules\Finances\Models\BudgetConsolidation;
use App\Repositories\Contracts\CrudRepositoryContract;
use Illuminate\Support\Collection;

interface BudgetConsolidationRepositoryContract
	extends CrudRepositoryContract {

	#region Inherited from CrudRepositoryContract

	/**
	 * @inheritdoc
	 * @return BudgetConsolidation|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return BudgetConsolidation
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Collection|BudgetConsolidation[]
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @inheritdoc
	 * @return Collection|BudgetConsolidation[]
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	#endregion

}