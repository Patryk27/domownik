<?php

namespace App\Repositories\Contracts;

use App\Models\Setting;
use Illuminate\Support\Collection;

interface SettingRepositoryContract
	extends CrudRepositoryContract {

	/**
	 * @param string $key
	 * @return mixed|null
	 */
	public function getValueByKey(string $key);

	/**
	 * @param int|null $userId
	 * @param string $key
	 * @return mixed|null
	 */
	public function getUserValueByKey($userId, string $key);

	#region Inherited from CrudRepositoryContract

	/**
	 * @inheritdoc
	 * @return Setting|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Setting
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Collection|Setting[]
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @inheritdoc
	 * @return Collection|Setting[]
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	#endregion

}