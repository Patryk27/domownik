<?php

namespace App\Modules\Finances\Repositories\Eloquent;

use App\Modules\Finances\Models\Transaction;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Repositories\Eloquent\AbstractCrudRepository;
use Illuminate\Support\Collection;

class TransactionRepository
	extends AbstractCrudRepository
	implements TransactionRepositoryContract {

	/**
	 * @var Transaction
	 */
	protected $model;

	/**
	 * @param int $id
	 * @param array $columns
	 * @return Transaction|null
	 */
	public function get(int $id, array $columns = ['*']) {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($id, $columns) {
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

		return $cache->rememberForever($cacheKey, function() use ($budgetId) {
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

		return $cache->rememberForever($cacheKey, function() use ($transactionCategoryId) {
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