<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\RepositoryException;
use App\Models\Model;
use App\Repositories\Contracts\CrudRepositoryContract;
use App\Support\UsesCache;
use Illuminate\Database\Connection;
use Illuminate\Foundation\Application;

abstract class AbstractCrudRepository
	implements CrudRepositoryContract {

	use UsesCache;

	/**
	 * @var Application
	 */
	protected $application;

	/**
	 * @var Connection
	 */
	protected $databaseConnection;

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * Returns model name representing the repository.
	 * @return string
	 */
	abstract protected function getModelName();

	/**
	 * AbstractCrudRepository constructor.
	 * @param Application $application
	 */
	public function __construct(Application $application) {
		$this->application = $application;
		$this->databaseConnection = $this->application->make(Connection::class);
		$this->model = $this->application->make($this->getModelName());
	}

	/**
	 * @inheritDoc
	 */
	public function get(int $id, array $columns = ['*']) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($id, $columns) {
			return $this->model->find($id, $columns);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getOrFail(int $id, array $columns = ['*']) {
		$model = $this->get($id, $columns);

		if (empty($model)) {
			throw new RepositoryException('A model of class %s with id %d could not have been found.', get_class($this->model), $id);
		}

		return $model;
	}

	/**
	 * @inheritDoc
	 */
	public function getBy(string $fieldName, $fieldValue, array $columns = ['*']) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($fieldName, $fieldValue, $columns) {
			return
				$this->model
					->where($fieldName, $fieldValue)
					->get($columns)
					->first();
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getAll(array $columns = ['*']) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($columns) {
			return $this->model->get($columns);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getPaginated($perPage = 15, $columns = ['*']) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($perPage, $columns) {
			return $this->model->paginate($perPage, $columns);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function delete(int $id): CrudRepositoryContract {
		$model = $this->get($id);

		if (!empty($model)) {
			$model->delete();
		}

		$this->getFlushCache()
			 ->flush();

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function persist(Model $model): CrudRepositoryContract {
		if (get_class($model) !== get_class($this->model)) {
			throw new RepositoryException('Repository was given a model of class \'%s\' which does not match its base model class \'%s\'.', get_class($model), get_class($this->model));
		}

		$model->save();

		$this->getFlushCache()
			 ->flush();

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function getCache() {
		return $this->model::getCache();
	}

	/**
	 * @inheritdoc
	 */
	public function getFlushCache() {
		return $this->model::getFlushCache();
	}

}