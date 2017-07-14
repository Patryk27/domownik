<?php

namespace App\Repositories\Eloquent;

use App\Models\TransactionSchedule;
use App\ValueObjects\ScheduledTransaction;
use App\Repositories\Contracts\TransactionRepositoryContract;
use App\Repositories\Contracts\TransactionScheduleRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TransactionScheduleRepository
	extends AbstractCrudRepository
	implements TransactionScheduleRepositoryContract {

	/**
	 * @inheritDoc
	 */
	public function getToDate(Carbon $date): Collection {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($date) {
			$stmt = $this->db
				->table('transaction_schedules AS ts')
				->where('ts.date', '<=', $date->format('Y-m-d'))
				->orderBy('ts.id');

			$rows = $stmt->get([
				'ts.id',
				'ts.transaction_id',
				'ts.date',
			]);

			return $this->convertToScheduledTransactionCollection($rows);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function getByBudgetId(int $budgetId, Carbon $dateFrom, Carbon $dateTo): Collection {
		$cacheKey = $this->getCacheKey(__FUNCTION__, func_get_args());
		$cache = $this->getCache();

		return $cache->rememberForever($cacheKey, function() use ($budgetId, $dateFrom, $dateTo) {
			$stmt = $this->db
				->table('transaction_schedules AS ts')
				->join('transactions AS t', 't.id', '=', 'ts.transaction_id')
				->where('t.parent_type', 'budget')
				->where('t.parent_id', $budgetId)
				->whereBetween('ts.date', [$dateFrom, $dateTo])
				->orderBy('ts.date')
				->orderBy('t.id');

			$rows = $stmt->get([
				'ts.id',
				'ts.transaction_id',
				'ts.date',
			]);

			return $this->convertToScheduledTransactionCollection($rows);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function deleteByTransactionId(int $transactionId): TransactionScheduleRepositoryContract {
		TransactionSchedule
			::where('transaction_id', $transactionId)
			->delete();

		$this
			->getFlushCache()
			->flush();

		return $this;
	}

	/**
	 * @return TransactionRepositoryContract
	 */
	protected function getTransactionRepository(): TransactionRepositoryContract {
		return $this->app->make(TransactionRepositoryContract::class);
	}

	/**
	 * @param Collection $rows
	 * @return Collection|ScheduledTransaction[]
	 */
	protected function convertToScheduledTransactionCollection(Collection $rows): Collection {
		$transactionRepository = $this->getTransactionRepository();

		$result = new Collection();

		foreach ($rows as $row) {
			$transaction = $transactionRepository->getOrFail($row->transaction_id);

			$result->push(new ScheduledTransaction(
				$row->id,
				$transaction,
				new Carbon($row->date)
			));
		}

		return $result;
	}

	/**
	 * @inheritDoc
	 */
	protected function getModelName(): string {
		return TransactionSchedule::class;
	}

}