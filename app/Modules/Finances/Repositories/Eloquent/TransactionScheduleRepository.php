<?php

namespace App\Modules\Finances\Repositories\Eloquent;

use App\Modules\Finances\Models\TransactionSchedule;
use App\Modules\Finances\Repositories\Contracts\TransactionRepositoryContract;
use App\Modules\Finances\Repositories\Contracts\TransactionScheduleRepositoryContract;
use App\Modules\Finances\ValueObjects\ScheduledTransaction;
use App\Repositories\Eloquent\AbstractCrudRepository;
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
			$stmt = $this->databaseConnection
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
			$stmt = $this->databaseConnection
				->table('transaction_schedules AS ts')
				->join('transactions AS t', 't.id', '=', 'ts.transaction_id')
				->where('t.parent_type', 'budget')
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
		return $this->application->make(TransactionRepositoryContract::class);
	}

	/**
	 * @param Collection $rows
	 * @return ScheduledTransaction[]|Collection
	 */
	protected function convertToScheduledTransactionCollection($rows) {
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
	protected function getModelName() {
		return TransactionSchedule::class;
	}

}