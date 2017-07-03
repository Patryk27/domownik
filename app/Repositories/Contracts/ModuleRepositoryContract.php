<?php

namespace App\Repositories\Contracts;

use App\Models\Module;
use Illuminate\Support\Collection;

interface ModuleRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * Returns module with given name or `null`.
	 * @param string $moduleName
	 * @return Module|null
	 */
	public function getByName(string $moduleName);

	#region Inherited from CrudRepositoryContract

	/**
	 * @inheritdoc
	 * @return Module|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Module
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Collection|Module[]
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @inheritdoc
	 * @return Collection|Module[]
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	#endregion

}