<?php

namespace App\Repositories\Eloquent;

use App\Models\Transaction;
use App\Repositories\Contracts\TransactionRepositoryContract;
use Illuminate\Support\Collection;

class TransactionRepository
	extends AbstractCrudRepository
	implements TransactionRepositoryContract {

	/**
	 * @var Transaction
	 */
	protected $model;

	/**
	 * @inheritdoc
	 */
	public function get(int $id, array $columns = ['*']) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function () use ($id, $columns) {
			return
				Transaction
					::with(['value', 'periodicity'])
					->find($id, $columns);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getByBudgetId(int $budgetId): Collection {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function () use ($budgetId) {
			return
				Transaction
					::where('parent_type', Transaction::PARENT_TYPE_BUDGET)
					->where('parent_id', $budgetId)
					->get();
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getByCategoryId(int $transactionCategoryId): Collection {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function () use ($transactionCategoryId) {
			return
				Transaction::where('category_id', $transactionCategoryId)
					->get();
		});
	}

	/**
	 * @inheritdoc
	 */
	protected function getModelName(): string {
		return Transaction::class;
	}

}