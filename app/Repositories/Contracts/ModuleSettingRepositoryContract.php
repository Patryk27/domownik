<?php

namespace App\Repositories\Contracts;

use App\Models\ModuleSetting;
use Illuminate\Support\Collection;

interface ModuleSettingRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * @param int $moduleId
	 * @param string $key
	 * @return mixed|null
	 */
	public function getValueByKey(int $moduleId, string $key);

	#region Inherited from CrudRepositoryContract

	/**
	 * @inheritdoc
	 * @return ModuleSetting|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return ModuleSetting
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Collection|ModuleSetting[]
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @inheritdoc
	 * @return Collection|ModuleSetting[]
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	#endregion

}