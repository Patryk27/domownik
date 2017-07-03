<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryContract
	extends CrudRepositoryContract {

	#region Inherited from CrudRepositoryContract

	/**
	 * @inheritdoc
	 * @return User|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return User
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * @inheritdoc
	 * @return Collection|User[]
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @inheritdoc
	 * @return Collection|User[]
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	#endregion

}