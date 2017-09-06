<?php

namespace App\Repositories\Contracts;

use App\Models\Model;
use Illuminate\Cache\TaggedCache;
use Illuminate\Support\Collection;

interface CrudRepositoryContract {

	/**
	 * Returns given model or 'null' if model could not have been found.
	 * @param int $id
	 * @param string[] $columns
	 * @return Model|null
	 */
	public function get(int $id, array $columns = ['*']);

	/**
	 * Returns given model or throws an exception if model could not have been found.
	 * @param int $id
	 * @param array $columns
	 * @return Model
	 */
	public function getOrFail(int $id, array $columns = ['*']);

	/**
	 * Returns all models matching given criteria.
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 * @param string[] $columns
	 * @param string|null $orderBy
	 * @return Collection
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @param string[] $columns
	 * @param string|null $orderBy
	 * @return Collection
	 */
	public function getAll(array $columns = ['*'], $orderBy = null): Collection;

	/**
	 * @param int $id
	 * @return CrudRepositoryContract
	 */
	public function delete(int $id): CrudRepositoryContract;

	/**
	 * Creates or updates model in the database.
	 * @param Model $model
	 * @return CrudRepositoryContract
	 */
	public function persist(Model $model): CrudRepositoryContract;

	/**
	 * Updates model in the database.
	 * Throws an exception if model does not already exist.
	 * @param Model $model
	 * @param int $id
	 * @return CrudRepositoryContract
	 */
	public function persistUpdate(Model $model, int $id): CrudRepositoryContract;

	/**
	 * @return TaggedCache
	 */
	public function getCache(): TaggedCache;

	/**
	 * @return TaggedCache
	 */
	public function getFlushCache(): TaggedCache;

}