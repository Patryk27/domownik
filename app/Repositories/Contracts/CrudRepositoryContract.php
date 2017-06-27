<?php

namespace App\Repositories\Contracts;

use App\Models\Model as BaseModel;
use Illuminate\Database\Eloquent\Model;
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
	 * @return Collection
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*']);

	/**
	 * @param string[] $columns
	 * @return Collection
	 */
	public function getAll(array $columns = ['*']);

	/**
	 * @param int $id
	 * @return mixed
	 */
	public function delete(int $id): CrudRepositoryContract;

	/**
	 * @param BaseModel $model
	 * @return CrudRepositoryContract
	 */
	public function persist(BaseModel &$model): CrudRepositoryContract;

	/**
	 * @return \Illuminate\Cache\TaggedCache
	 */
	public function getCache();

	/**
	 * @return \Illuminate\Cache\TaggedCache
	 */
	public function getFlushCache();

}